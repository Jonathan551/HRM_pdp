<?php
/* views/master-penilaian/view.php */
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */

$this->title = 'Penilaian #'.$model->id_penilaian;
$this->params['breadcrumbs'][] = ['label' => 'Master Penilaian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

$fmt = static function (?string $d): string {
    if (!$d) return '-';
    $ts = strtotime($d);
    return $ts ? date('d-m-Y', $ts) : $d;
};


$periode   = $model->periode ?? null;
$endStr    = $periode->tanggal_selesai ?? null;
$isLocked  = false;
if ($periode) {
    $end = $endStr ? \DateTimeImmutable::createFromFormat('Y-m-d', $endStr) ?: new \DateTimeImmutable($endStr) : null;
    $today = new \DateTimeImmutable('today');
    $isLocked = ($end && $end < $today) || (strtolower((string)$periode->status) === 'locked');
}
?>
<div class="master-penilaian-view">

    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <div class="alert alert-<?= in_array($type, ['success','info','warning','danger','error']) ? ($type === 'error' ? 'danger' : $type) : 'info' ?>" role="alert" style="margin-top:10px;">
         <?= is_array($message)
                ? implode('<br>', array_map(function ($msg) { return Html::encode($msg); }, $message))
                : Html::encode($message)
            ?>
        </div>
    <?php endforeach; ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!$isLocked): ?>
            <?= Html::a('Update', ['update', 'id_penilaian' => $model->id_penilaian], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id_penilaian' => $model->id_penilaian], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Yakin ingin menghapus data ini?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php else: ?>
            <?= Html::tag('span', 'Periode berakhir — hanya dapat dilihat', ['class'=>'badge bg-secondary']) ?>
        <?php endif; ?>

        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>

        <?= Html::button('Detail Penilaian', [
            'class' => 'btn btn-success',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#detailPenilaianModal'
        ]) ?>

        <?= Html::a('Print Laporan', ['report/cetak', 'id' => $model->id_penilaian], [
            'class' => 'btn btn-light',
            'target' => '_blank'
        ]) ?>
    </p>

    <div class="modal fade" id="detailPenilaianModal" tabindex="-1" aria-labelledby="detailPenilaianModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailPenilaianModalLabel">Detail Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Anchor</th>
                            <th>Skala</th>
                            <th>Nilai</th>
                            <th>Bobot</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($model->detailPenilaian as $detail): ?>
                            <tr>
                                <td><?= $detail->kriteria->nama_kriteria ?? '-' ?></td>
                                <td><?= $detail->anchor->deskripsi ?? '-' ?></td>
                                <td><?= $detail->anchor->level_anchor ?? '-' ?></td>
                                <td><?= $detail->anchor->nilai_anchor ?? '-' ?></td>
                                <td><?= $detail->kriteria->bobot ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'User',
                'value' => $model->user->nama ?? '-',
            ],
            [
                'label' => 'Periode Penilaian',
                'value' => $periode
                    ? sprintf('%s (%s s/d %s)', $periode->nama, $fmt($periode->tanggal_mulai ?? null), $fmt($periode->tanggal_selesai ?? null))
                    : '-',
            ],
            [
                'attribute' => 'nilai_akhir',
                'label' => 'Nilai Akhir',
            ],
            [
                'label' => 'Status Nilai',
                'value' => $model->kategori->nama_kategori ?? '-',
            ],
            [
                'attribute' => 'presentase_absensi',
                'label' => 'Presentase Absensi',
            ],
            [
                'attribute' => 'catatan',
                'label' => 'Catatan',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'rekomendasi',
                'label' => 'Rekomendasi',
                'format' => 'ntext',
            ],
        ],
    ]) ?>

</div>
