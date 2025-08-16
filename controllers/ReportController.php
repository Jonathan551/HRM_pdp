<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use app\models\DetailPenilaian;
use app\models\MasterKategori;
use app\models\MasterPenilaian;

class ReportController extends Controller
{
    public function actionCetak($id)
    {
        $penilaian = MasterPenilaian::find()
            ->with(['user.jabatan', 'user.departement'])
            ->where(['id_penilaian' => $id])
            ->one();

        if (!$penilaian) {
            throw new \yii\web\NotFoundHttpException("Data tidak ditemukan");
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

        $hasilKategori = $kategoriAktif ? $kategoriAktif->nama_kategori : "Tidak dikategorikan";

        $allKategori = MasterKategori::find()->orderBy(['nilai_max' => SORT_DESC])->all();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText("Laporan Penilaian Kinerja Karyawan", ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addTextBreak(1);

        $section->addText("Data Karyawan", ['bold' => true]);
        $section->addText("Nama : " . $penilaian->user->nama);
        $section->addText("No Hp : " . $penilaian->user->nomor_hp);
        $section->addText("Tanggal_Lahir : " . $penilaian->user->tanggal_lahir);
        $section->addText("Jenis Kelamin : " . $penilaian->user->jenis_kelamin);
        $section->addText("Bagian : " . ($penilaian->user->departement ? $penilaian->user->departement->nama_departement : '-'));
        $section->addText("Jabatan : " . ($penilaian->user->jabatan ? $penilaian->user->jabatan->nama_jabatan : '-'));
        $section->addText("Periode Penilaian : {$penilaian->periode_awal} - {$penilaian->periode_akhir}");
        $section->addTextBreak(1);

        $table = $section->addTable(['borderSize'=>6,'borderColor'=>'000000']);
        $table->addRow();
        $table->addCell(2000)->addText("Kriteria");
        $table->addCell(3000)->addText("Deskripsi");
        $table->addCell(1000)->addText("Nilai Skala (1-5)");
        $table->addCell(1000)->addText("Bobot");
        $table->addCell(1500)->addText("Nilai Tertimbang (Nilai x Bobot)");
        $table->addCell(3000)->addText("Deskripsi Perilaku");

        $totalBobot = 0;
        $totalSkor = 0;
        foreach ($detail as $d) {
            $nilai = $d->anchor ? $d->anchor->nilai_anchor : 0;
            $skor = $nilai * $d->kriteria->bobot;

            $totalBobot += $d->kriteria->bobot;
            $totalSkor += $skor;

            $table->addRow();
            $table->addCell(2000)->addText($d->kriteria->nama_kriteria);
            $table->addCell(3000)->addText($d->kriteria->deskripsi);
            $table->addCell(1000)->addText($nilai);
            $table->addCell(1000)->addText($d->kriteria->bobot);
            $table->addCell(1500)->addText(number_format($skor,2));
            $table->addCell(3000)->addText($d->anchor ? $d->anchor->deskripsi : '-');
        }

        $table->addRow();
        $table->addCell(2000)->addText("Jumlah", ['bold'=>true]);
        $table->addCell(3000)->addText("");
        $table->addCell(1000)->addText("");
        $table->addCell(1000)->addText($totalBobot, ['bold'=>true]);
        $table->addCell(1500)->addText(number_format($totalSkor,2), ['bold'=>true]);
        $table->addCell(3000)->addText("");

        $section->addTextBreak(1);

        $section->addText("Total Skor Kinerja Tertimbang : " . number_format($rata,2));
        $section->addText("Hasil Penilaian Kinerja Karyawan : " . $hasilKategori);
        $section->addTextBreak(1);

        $section->addText("Kategori Penilaian", ['bold' => true]);
        foreach ($allKategori as $kat) {
            $section->addText($kat->nama_kategori . " = Skor antara " . $kat->nilai_min . " - " . $kat->nilai_max);
        }

        $filename = "Laporan_Kinerja_".$penilaian->user->nama.".docx";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save("php://output");
        exit;
    }
}
