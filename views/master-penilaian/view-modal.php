<?php

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */
?>

<div class="master-penilaian-view">
     <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id_penilaian',
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
            'catatan',
        ],
    ]) ?>
</div>
