<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use app\models\MasterPenilaian;
use app\models\DetailPenilaian;
use app\models\MasterKategori;

class ReportService extends Component
{
    public string $dompdfPath = '@vendor/dompdf/dompdf';

    /** @return array{path:string, filename:string} */
    public function buildPenilaianPdf(int $id): array
    {
        // Why: validasi dependency di sini supaya komponen tetap bisa di-instantiate
        if (!class_exists(PhpWord::class)) {
            throw new Exception('Dependency phpoffice/phpword belum terpasang.');
        }
        $dompdfDir = Yii::getAlias($this->dompdfPath);
        if (!is_dir($dompdfDir)) {
            throw new Exception("DOMPDF path tidak ditemukan: {$dompdfDir}");
        }

        $penilaian = MasterPenilaian::find()
            ->with(['user.jabatan', 'user.departement'])
            ->where(['id_penilaian' => $id])
            ->one();
        if (!$penilaian) {
            throw new Exception('Data penilaian tidak ditemukan.');
        }

        $detail = DetailPenilaian::find()
            ->with(['kriteria','anchor'])
            ->where(['id_penilaian' => $id])
            ->all();

        $rata = $penilaian->nilai_akhir;

        $kategoriAktif = MasterKategori::find()
            ->where(['<=', 'nilai_min', $rata])
            ->andWhere(['>=', 'nilai_max', $rata])
            ->one();
        $hasilKategori = $kategoriAktif? $kategoriAktif->nama_kategori : 'Tidak dikategorikan';
        $allKategori   = MasterKategori::find()->orderBy(['nilai_max' => SORT_DESC])->all();

        // Foto
        $fotoPath = null;
        if (!empty($penilaian->user->foto)) {
            $try = Yii::getAlias('@webroot/uploads/users/' . $penilaian->user->foto);
            if (is_file($try)) { $fotoPath = $try; }
        }
        if (!$fotoPath) {
            $fallback = Yii::getAlias('@webroot/images/no-avatar.png');
            if (is_file($fallback)) { $fotoPath = $fallback; }
        }

        // ===== Build dokumen =====
        $phpWord = new PhpWord();
        $phpWord->addTableStyle('HeaderTable', [
            'borderSize' => 0, 'borderColor' => 'FFFFFF',
            'cellMarginTop' => 80, 'cellMarginBottom' => 80,
            'cellMarginLeft' => 120, 'cellMarginRight' => 120,
        ]);
        $phpWord->addTableStyle('InfoTable', [
            'borderSize' => 0, 'borderColor' => 'FFFFFF',
            'cellMarginTop' => 20, 'cellMarginBottom' => 20,
            'cellMarginLeft' => 80, 'cellMarginRight' => 80,
        ]);
        $phpWord->addTableStyle('DetailTable', [
            'borderSize' => 6, 'borderColor'=> '000000',
            'alignment'  => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ]);

        $section = $phpWord->addSection();
        $section->addText("Laporan Penilaian Kinerja Karyawan", ['bold'=>true,'size'=>14], ['alignment'=>\PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);

        $labelStyle = ['bold' => true];
        $pTight     = ['spaceBefore'=>0,'spaceAfter'=>0,'lineHeight'=>1.05];

        $headerTable = $section->addTable('HeaderTable');
        $headerTable->addRow();
        $leftCell = $headerTable->addCell(2200, ['valign'=>'center']);
        if ($fotoPath) {
            $leftCell->addImage($fotoPath, ['width'=>110,'wrappingStyle'=>'inline']);
        } else {
            $leftCell->addText('(Foto tidak tersedia)', ['italic'=>true], ['alignment'=>'center']);
        }
        $rightCell = $headerTable->addCell(7800);
        $rightCell->addText('Data Karyawan', ['bold'=>true,'size'=>12], ['spaceAfter'=>120]);

        $infoTable = $rightCell->addTable('InfoTable');
        $addInfo = function($table, $label, $value) use ($labelStyle, $pTight) {
            $table->addRow();
            $table->addCell(2200)->addText($label, $labelStyle, $pTight);
            $table->addCell(5600)->addText(': ' . ($value ?? '-'), [], $pTight);
        };
        $addInfo($infoTable, 'Nama', $penilaian->user->nama ?? '-');
        $addInfo($infoTable, 'No Hp', $penilaian->user->nomor_hp ?? '-');
        $addInfo($infoTable, 'Tanggal Lahir', $penilaian->user->tanggal_lahir ?? '-');
        $addInfo($infoTable, 'Jenis Kelamin', $penilaian->user->jenis_kelamin ?? '-');
        $addInfo($infoTable, 'Bagian', $penilaian->user->departement->nama_departement ?? '-');
        $addInfo($infoTable, 'Jabatan', $penilaian->user->jabatan->nama_jabatan ?? '-');
        $addInfo($infoTable, 'Periode Penilaian', "{$penilaian->periode_awal} - {$penilaian->periode_akhir}");
        $section->addTextBreak(1);

        $pCenter    = ['alignment'=>\PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $cellCenter = ['valign'=>'center'];

        $table = $section->addTable('DetailTable');
        $table->addRow();
        $table->addCell(2000,$cellCenter)->addText("Kriteria", [], $pCenter);
        $table->addCell(3000,$cellCenter)->addText("Deskripsi", [], $pCenter);
        $table->addCell(1000,$cellCenter)->addText("Nilai Skala (1-5)", [], $pCenter);
        $table->addCell(1000,$cellCenter)->addText("Bobot", [], $pCenter);
        $table->addCell(1500,$cellCenter)->addText("Nilai Tertimbang", [], $pCenter);
        $table->addCell(3000,$cellCenter)->addText("Deskripsi Perilaku", [], $pCenter);

        $totalBobot = 0; $totalSkor = 0;
        foreach ($detail as $d) {
            $nilai = $d->anchor->nilai_anchor ?? 0;
            $bobot = $d->kriteria->bobot ?? 0;
            $skor  = $nilai * $bobot;
            $totalBobot += $bobot;
            $totalSkor  += $skor;

            $table->addRow();
            $table->addCell(2000,$cellCenter)->addText($d->kriteria->nama_kriteria ?? '-', [], $pCenter);
            $table->addCell(3000,$cellCenter)->addText($d->kriteria->deskripsi ?? '-', [], $pCenter);
            $table->addCell(1000,$cellCenter)->addText((string)$nilai, [], $pCenter);
            $table->addCell(1000,$cellCenter)->addText((string)$bobot, [], $pCenter);
            $table->addCell(1500,$cellCenter)->addText(number_format($skor,2), [], $pCenter);
            $table->addCell(3000,$cellCenter)->addText($d->anchor->deskripsi ?? '-', [], $pCenter);
        }
        $table->addRow();
        $table->addCell(2000,$cellCenter)->addText("Jumlah", ['bold'=>true], $pCenter);
        $table->addCell(3000,$cellCenter)->addText("", [], $pCenter);
        $table->addCell(1000,$cellCenter)->addText("", [], $pCenter);
        $table->addCell(1000,$cellCenter)->addText((string)$totalBobot, ['bold'=>true], $pCenter);
        $table->addCell(1500,$cellCenter)->addText(number_format($totalSkor,2), ['bold'=>true], $pCenter);
        $table->addCell(3000,$cellCenter)->addText("", [], $pCenter);

        $section->addTextBreak(1);
        $section->addText("Total Skor Kinerja Tertimbang : " . number_format($rata, 2));
        $section->addText("Hasil Penilaian Kinerja Karyawan : " . $hasilKategori);
        $section->addTextBreak(1);

        $section->addText("Kategori Penilaian", ['bold'=>true]);
        foreach ($allKategori as $kat) {
            $section->addText($kat->nama_kategori . " = Skor antara " . $kat->nilai_min . " - " . $kat->nilai_max);
        }
        $section->addTextBreak(1);
        $section->addText("Catatan Penilaian", ['bold'=>true]);
        $section->addText($penilaian->catatan ? (string)$penilaian->catatan : '-', [], ['alignment'=>'both']);

        // Render PDF via DOMPDF
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath($dompdfDir);

        $safeName = "Laporan_Kinerja_" . preg_replace('/[^\w\-]+/u','_', ($penilaian->user->nama ?? 'User')) . ".pdf";

        $tmpBase = tempnam(sys_get_temp_dir(), 'laporan_');
        if ($tmpBase === false) { throw new Exception('Gagal membuat file sementara.'); }
        @unlink($tmpBase);
        $tmpFile = $tmpBase . '.pdf';

        $writer = IOFactory::createWriter($phpWord, 'PDF');
        $writer->save($tmpFile);

        return ['path'=>$tmpFile, 'filename'=>$safeName];
    }
}