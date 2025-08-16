<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */

$this->title = $model->id_penilaian;
$this->params['breadcrumbs'][] = ['label' => 'Penilaian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-penilaian-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::button('Detail Penilaian', [
            'class' => 'btn btn-success',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#detailPenilaianModal'
        ]) ?>
        <?= Html::a('Print Laporan', ['report/cetak', 'id' => $model->id_penilaian], [
            'class' => 'btn btn-primary',
            'target' => '_blank' 
        ]) ?>
    </p>

    <div class="modal fade" id="detailPenilaianModal" tabindex="-1" aria-labelledby="detailPenilaianModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailPenilaianModalLabel">Detail Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th>Anchor</th>
                                <th>Level</th>
                                <th>Nilai</th>
                                <th>Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model->detailPenilaian as $detail): ?>
                                <tr>
                                    <td><?= $detail->kriteria ? $detail->kriteria->nama_kriteria : '-' ?></td>
                                    <td><?= $detail->anchor ? $detail->anchor->deskripsi : '-' ?></td>
                                    <td><?= $detail->anchor ? $detail->anchor->level_anchor : '-' ?></td>
                                    <td><?= $detail->anchor ? $detail->anchor->nilai_anchor : '-' ?></td>
                                    <td><?= $detail->kriteria ? $detail->kriteria->bobot : '-' ?></td>
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
                'attribute' => 'id_users',
                'label' => 'User',
                'value' => function ($model) {
                    return $model->user ? $model->user->nama : '-';
                },
            ],
            'nilai_akhir',
            [
                'attribute' => 'periode_awal',
                'label' => 'Periode Awal Penilaian',
                'format' => ['date', 'php:d-m-Y H:i'],
            ],
            [
                'attribute' => 'periode_akhir',
                'label' => 'Periode Awal Akhir',
                'format' => ['date', 'php:d-m-Y H:i'],
            ],
            [
                'attribute' => 'id_kategori',
                'value' => function($model) {
                    return $model->kategori ? $model->kategori->nama_kategori : '-';
                },
                'label' => 'Status Nilai',
            ],
            [
                'attribute' => 'presentase_absensi',
                'label' => 'Presentase Absensi',
            ],
        ],
    ]) ?>

</div>
