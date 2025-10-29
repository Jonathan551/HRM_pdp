<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Master Anchor';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-anchor-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create Master Anchor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => "Showing {begin}-{end} of {totalCount} items.",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '#',
                'headerOptions' => [
                    'style' => 'color:#9c27b0; font-weight:600;'
                ],
            ],

            [
                'attribute' => 'nama_departemen',
                'label'     => 'Departemen',
                'value'     => function ($model) {
                    return $model['nama_departement'] ?: '-';
                },
                'headerOptions' => [
                    'style' => 'color:#9c27b0; font-weight:600;'
                ],
                'contentOptions' => [
                    'style' => 'white-space:nowrap;'
                ],
            ],

            [
                'attribute' => 'nama_kriteria',
                'label'     => 'Kriteria',
                'headerOptions' => [
                    'style' => 'color:#9c27b0; font-weight:600;'
                ],
            ],

            [
                'attribute' => 'jumlah_skala',
                'label'     => 'Jumlah Level/Skala',
                'contentOptions' => [
                    'style' => 'text-align:center; width:140px;'
                ],
                'headerOptions' => [
                    'style' => 'text-align:center; color:#9c27b0; font-weight:600;'
                ],
            ],

            [
                'label' => 'Aksi',
                'format' => 'raw',
                'value' => function ($model) {
                    $viewUrl = Url::to([
                        'master-anchor/view',
                        'id_kriteria' => $model['id_kriteria'],
                    ]);

                    return Html::a(
                        '<i class="material-icons" style="font-size:18px;">visibility</i>',
                        $viewUrl,
                        [
                            'title' => 'Lihat detail level anchor',
                            'style' => 'color:#9c27b0;'
                        ]
                    );
                },
                'contentOptions' => [
                    'style' => 'text-align:center; width:80px;'
                ],
                'headerOptions' => [
                    'style' => 'text-align:center; color:#9c27b0; font-weight:600;'
                ],
            ],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
        ],
    ]); ?>
</div>
