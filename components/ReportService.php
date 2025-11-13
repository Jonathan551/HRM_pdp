<?php
// app/components/ReportService.php (PATCH FOKUS DI BAGIAN YANG BERUBAH SAJA)
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use app\models\MasterPenilaian;
use app\models\DetailPenilaian;
use app\models\MasterKategori;
use app\models\MasterProfile;
use app\models\MasterPeriode;

class ReportService extends Component
{
    public string $dompdfPath = '@vendor/dompdf/dompdf';

    /** @return array{path:string, filename:string} */
    public function buildPenilaianPdf(int $id): array
    {
        if (!class_exists(PhpWord::class)) {
            throw new Exception('Dependency phpoffice/phpword belum terpasang.');
        }
        $dompdfDir = Yii::getAlias($this->dompdfPath);
        if (!is_dir($dompdfDir)) {
            throw new Exception("DOMPDF path tidak ditemukan: {$dompdfDir}");
        }

        $penilaian = MasterPenilaian::find()
            ->with(['user.jabatan', 'user.departement', 'periode'])
            ->where(['id_penilaian' => $id])
            ->one();
        if (!$penilaian) {
            throw new Exception('Data penilaian tidak ditemukan.');
        }

        $fmt = static function (?string $d): string {
            if (!$d) return '-';
            // terima 'Y-m-d' atau DateTime string lain
            $ts = strtotime($d);
            return $ts ? date('d-m-Y', $ts) : $d;
        };

        $periode = $penilaian->periode ?: MasterPeriode::find()->orderBy(['tanggal_mulai'=>SORT_DESC])->one();
        $periodeMulai   = $fmt($periode->tanggal_mulai ?? null);
        $periodeSelesai = $fmt($periode->tanggal_selesai ?? null);

        $detail = DetailPenilaian::find()
            ->with(['kriteria','anchor'])
            ->where(['id_penilaian' => $id])
            ->all();

        $rata = $penilaian->nilai_akhir;

        $kategoriAktif = MasterKategori::find()
            ->where(['<=', 'nilai_min', $rata])
            ->andWhere(['>=', 'nilai_max', $rata])
            ->one();
        $hasilKategori = $kategoriAktif ? $kategoriAktif->nama_kategori : 'Tidak dikategorikan';
        $allKategori   = MasterKategori::find()->orderBy(['nilai_max' => SORT_DESC])->all();

        $profile = MasterProfile::find()->one();
        $logoPath = null;
        if ($profile && !empty($profile->logo)) {
            $tryLogo = Yii::getAlias('@webroot/uploads/' . $profile->logo);
            if (is_file($tryLogo)) $logoPath = $tryLogo;
        }

        $fotoPath = null;
        if (!empty($penilaian->user->foto)) {
            $try = Yii::getAlias('@webroot/uploads/users/' . $penilaian->user->foto);
            if (is_file($try)) $fotoPath = $try;
        }
        if (!$fotoPath) {
            $fallback = Yii::getAlias('@webroot/images/no-avatar.png');
            if (is_file($fallback)) $fotoPath = $fallback;
        }

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $phpWord->addTableStyle('HeaderTable', ['borderSize'=>0,'borderColor'=>'FFFFFF']);
        $phpWord->addTableStyle('InfoTable',   ['borderSize'=>6,'borderColor'=>'000000']);
        $phpWord->addTableStyle('DetailTable', ['borderSize'=>6,'borderColor'=>'000000','alignment'=>JcTable::CENTER]);

        $section = $phpWord->addSection([
            'marginTop'=>800,'marginBottom'=>800,'marginLeft'=>800,'marginRight'=>800,
        ]);


        $headerTable = $section->addTable('HeaderTable');
        $headerTable->addRow(500, ['exactHeight'=>true]);
        $cellLogo  = $headerTable->addCell(2000, ['valign'=>'center']);
        $cellTitle = $headerTable->addCell(8000, ['valign'=>'center']);
        if ($logoPath) {
            $run = $cellLogo->addTextRun(['alignment'=>Jc::CENTER]);
            $run->addImage($logoPath, ['width'=>90, 'height'=>90]);
        }
        $cellTitle->addText('Laporan Penilaian Kinerja Karyawan', ['bold'=>true,'size'=>14,'underline'=>'single'], ['alignment'=>Jc::CENTER]);

        $section->addTextBreak(1);


        $infoTable = $section->addTable('InfoTable');
        $infoTable->addRow();
        $left  = $infoTable->addCell(7000);
        $right = $infoTable->addCell(3000, ['valign'=>'center']);

        $addInfo = function($table, $label, $value) {
            $table->addRow();
            $table->addCell(2500)->addText($label, ['bold'=>true]);
            $table->addCell(4000)->addText(': '.$value);
        };
        $t = $left->addTable('HeaderTable');
        $addInfo($t,'Nama',           $penilaian->user->nama ?? '-');
        $addInfo($t,'No HP',          $penilaian->user->nomor_hp ?? '-');
        $addInfo($t,'Tanggal Lahir',  $fmt($penilaian->user->tanggal_lahir ?? null));
        $addInfo($t,'Jenis Kelamin',  $penilaian->user->jenis_kelamin ?? '-');
        $addInfo($t,'Bagian',         $penilaian->user->departement->nama_departement ?? '-');
        $addInfo($t,'Jabatan',        $penilaian->user->jabatan->nama_jabatan ?? '-');
        $addInfo($t,'Periode Penilaian', "{$periodeMulai} - {$periodeSelesai}");

         if ($fotoPath) {
            $imgWrap = $right->addTable('HeaderTable');
            $imgWrap->addRow(1800, ['exactHeight'=>true]); // tinggi tetap
            $imgCell = $imgWrap->addCell(3000, ['valign'=>'center','borderSize'=>0,'borderColor'=>'FFFFFF']);
            $runFoto = $imgCell->addTextRun(['alignment'=>Jc::CENTER]); // center horizontal
            $runFoto->addImage($fotoPath, ['width'=>90,'height'=>90]);
        } else {
            $right->addText('(Foto tidak tersedia)', ['italic'=>true], ['alignment'=>Jc::CENTER]);
        }

        $section->addTextBreak(1);

        $pCenter    = ['alignment'=>Jc::CENTER];
        $cellCenter = ['valign'=>'center'];
        $table = $section->addTable('DetailTable');

        $headers = ['Kriteria', 'Deskripsi', 'Nilai Skala (1–5)', 'Bobot', 'Nilai Tertimbang', 'Deskripsi Perilaku'];
        $widths  = [2000, 3000, 1000, 1000, 1500, 3000];

        $table->addRow();
        foreach ($headers as $i => $h) {
            $table->addCell($widths[$i], $cellCenter)->addText($h, ['bold'=>true], $pCenter);
        }

        $totalBobot = 0; $totalSkor = 0;
        foreach ($detail as $d) {
            $nilai = $d->anchor->nilai_anchor ?? 0;
            $bobot = $d->kriteria->bobot ?? 0;
            $skor  = $nilai * $bobot;
            $totalBobot += $bobot;
            $totalSkor  += $skor;

            $table->addRow();
            $table->addCell($widths[0])->addText($d->kriteria->nama_kriteria ?? '-', [], $pCenter);
            $table->addCell($widths[1])->addText($d->kriteria->deskripsi ?? '-');
            $table->addCell($widths[2])->addText((string)$nilai, [], $pCenter);
            $table->addCell($widths[3])->addText((string)$bobot, [], $pCenter);
            $table->addCell($widths[4])->addText(number_format($skor,2), [], $pCenter);
            $table->addCell($widths[5])->addText($d->anchor->deskripsi ?? '-');
        }

        $table->addRow();
        $table->addCell($widths[0], ['gridSpan'=>3])->addText('Total', ['bold'=>true], $pCenter);
        $table->addCell($widths[3])->addText(number_format($totalBobot,2), ['bold'=>true], $pCenter);
        $table->addCell($widths[4])->addText(number_format($totalSkor,2),  ['bold'=>true], $pCenter);
        $table->addCell($widths[5])->addText('', [], $pCenter);

        $section->addTextBreak(1);
        $section->addText("Total Skor Kinerja Tertimbang : " . number_format($rata, 2));
        $section->addText("Hasil Penilaian Kinerja Karyawan : " . $hasilKategori);

        $section->addTextBreak(1);
        $section->addText("Kategori Penilaian", ['bold'=>true]);
        foreach ($allKategori as $kat) {
            $section->addText("• {$kat->nama_kategori} = Skor antara {$kat->nilai_min} - {$kat->nilai_max}");
        }

        $section->addTextBreak(1);
        $section->addText("Catatan Penilaian", ['bold'=>true]);
        $section->addText($penilaian->catatan ? (string)$penilaian->catatan : '-', [], ['alignment'=>'both']);

        $footer = $section->addFooter();
        $footer->addPreserveText(sprintf('%s — Halaman {PAGE} dari {NUMPAGES}', Yii::$app->name), [], ['alignment'=>Jc::CENTER]);


        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath($dompdfDir);

        $safeName = "Laporan_Kinerja_" . preg_replace('/[^\w\-]+/u','_', ($penilaian->user->nama ?? 'User')) . ".pdf";
        $tmpBase = tempnam(sys_get_temp_dir(), 'laporan_');
        if ($tmpBase === false) throw new Exception('Gagal membuat file sementara.');
        @unlink($tmpBase);
        $tmpFile = $tmpBase . '.pdf';

        $writer = IOFactory::createWriter($phpWord, 'PDF');
        $writer->save($tmpFile);

        return ['path'=>$tmpFile, 'filename'=>$safeName];
    }
}
