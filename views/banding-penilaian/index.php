<?php

use app\models\BandingPenilaian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BandingPenilaiansearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Banding Penilaian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banding-penilaian-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id_penilaian',
                'label' => 'Nomor Laporan Penilaian',
            ],
            [
                'attribute' => 'id_users',
                'label' => 'Karyawan',
                'value' => function ($model) {
                    return $model->user ? $model->user->nama : '-';
                },
            ],
            'status',
             [
                'attribute' => 'tanggal_banding',
                'format' => ['date', 'php:d-m-Y'],
            ],
            'alasan:ntext',
            'review:ntext',
            [
                'attribute' => 'tanggal_review',
                'format' => ['date', 'php:d-m-Y'],
            ],
           [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{review}',   // hanya tampilkan tombol "review"
                'buttons' => [
                    'review' => function ($url, $model, $key) {
                        return Html::a(
                            '<i class="fas fa-search"></i> Review',
                            ['banding-penilaian/banding', 'id_banding' => $model->id_banding], 
                            [
                                'class' => 'btn btn-sm btn-primary',
                                'title' => 'Review data',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
