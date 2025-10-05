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
            ['label' => 'Tanggal Banding', 'value' => 'tanggalBandingDisplay', 'format' => 'raw'],
            'alasan:ntext',
            'review:ntext',
            ['label' => 'Tanggal Review', 'value' => 'tanggalReviewDisplay', 'format' => 'raw'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{reviewStatus}', 
                'buttons' => [
                    'reviewStatus' => function ($url, $model, $key) {
                        if ($model->status === BandingPenilaian::STATUS_REVIEW) {
                            return Html::a(
                                '<i class="fas fa-search"></i> Review',
                                ['banding-penilaian/banding', 'id_banding' => $model->id_banding], 
                                ['class' => 'btn btn-sm btn-primary', 'title' => 'Review data']
                            );
                        }
                        $badgeClass = ($model->status === BandingPenilaian::STATUS_DITERIMA)
                            ? 'badge bg-success'
                            : 'badge bg-danger';

                        return Html::tag('span', Html::encode($model->status), [
                            'class' => $badgeClass,
                            'style' => 'padding:6px 10px; font-weight:600;',
                            'title' => 'Keputusan final',
                        ]);
                    },
                ],
                'contentOptions' => ['style' => 'width:160px; text-align:right;'],
            ],
        ],
    ]); ?>
</div>
