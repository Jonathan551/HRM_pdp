<?php

use app\models\MasterPenilaian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaiansearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Banding Penilaian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penilaian-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id_users',
                'label' => 'Karyawan',
                'value' => function ($model) {
                    return $model->user ? $model->user->nama : '-';
                },
            ],
            'nilai_akhir',
            [
                'attribute' => 'periode_awal',
                'label' => 'Periode Awal Penilaian',
                'format' => ['date', 'php:d-m-Y'],
            ],
            [
                'attribute' => 'periode_akhir',
                'label' => 'Periode Akhir Penilaian',
                'format' => ['date', 'php:d-m-Y'],
            ],
            [
                'attribute' => 'id_kategori',
                'value' => function($model) {
                    return $model->kategori ? $model->kategori->nama_kategori : 'Belum Ada';
                },
                'label' => 'Status Nilai',
            ],
            [
                'attribute' => 'presentase_absensi',
                'label' => 'Presentase Absensi',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {banding}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_penilaian' => $model->id_penilaian]);
                },
                'buttons' => [
                    'banding' => function ($url, $model) {
                        
                        if ($model->banding) {
                            return Html::tag('span', $model->banding->status, [
                                'class' => 'badge badge-' . ($model->banding->status == 'Review' ? 'warning' : ($model->banding->status == 'Diterima' ? 'success' : ($model->banding->status == 'Ditolak' ? 'danger' : "Null" )))
                            ]);
                        } else {
                            return Html::a('Ajukan Banding', 
                                ['banding-penilaian/create', 'id_penilaian' => $model->id_penilaian], 
                                [
                                    'title' => 'Ajukan Banding',
                                    'class' => 'btn btn-sm btn-outline-primary'
                                ]
                            );
                        }
                    }
                ]
            ],
        ],
    ]); ?>

    <hr>
</div>
