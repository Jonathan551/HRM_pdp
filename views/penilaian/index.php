<?php

use app\models\MasterPenilaian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaiansearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Penilaian';
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
                'class' => ActionColumn::className(),
                'template' => '{view}', // hanya tombol View
                'urlCreator' => function ($action, MasterPenilaian $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_penilaian' => $model->id_penilaian]);
                }
            ],
        ],
    ]); ?>

    <hr>

    <h3>Grafik Nilai Akhir</h3>
    <canvas id="nilaiChart" width="400" height="150"></canvas>

</div>

<?php
    $labels = [];
    $data   = [];

    foreach ($dataProvider->models as $model) {
        $labels[] = date('d-m-Y', strtotime($model->periode_awal));
        $data[]   = (float)$model->nilai_akhir;
    }

    $labelsJson = json_encode($labels);
    $dataJson   = json_encode($data);

    $this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
    $js = <<<JS
    var ctx = document.getElementById('nilaiChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: $labelsJson,
            datasets: [{
                label: 'Nilai Akhir',
                data: $dataJson,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });
    JS;

    $this->registerJs($js);
?>
