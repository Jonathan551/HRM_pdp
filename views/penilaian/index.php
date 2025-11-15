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

$fmt = static function (?string $d): string {
    if (!$d) return '-';
    $ts = strtotime($d);
    return $ts ? date('d-m-Y', $ts) : $d;
};

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
                    return $model->user->nama ?? '-';
                },
            ],

            [
                'label' => 'Periode',
                'value' => function ($model) use ($fmt) {
                    $p = $model->periode ?? null;
                    if (!$p) return '-';
                    $mulai   = $fmt($p->tanggal_mulai ?? null);
                    $selesai = $fmt($p->tanggal_selesai ?? null);
                    return ($p->nama ?? 'Periode') . " — {$mulai} – {$selesai}";
                },
            ],

            [
                'attribute' => 'nilai_akhir',
                'label' => 'Nilai Akhir',
            ],

            [
                'attribute' => 'id_kategori',
                'label' => 'Status Nilai',
                'value' => function($model) {
                    return $model->kategori->nama_kategori ?? 'Belum Ada';
                },
            ],
            [
                'attribute' => 'presentase_absensi',
                'label' => 'Presentase Absensi',
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'urlCreator' => function ($action, MasterPenilaian $model) {
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
        /** @var MasterPenilaian $model */
        $p = $model->periode ?? null;
        if ($p) {
            $label = ($p->nama ?? 'Periode') . ' (' . $fmt($p->tanggal_mulai ?? null) . ' – ' . $fmt($p->tanggal_selesai ?? null) . ')';
        } else {
            $label = 'Periode -';
        }
        $labels[] = $label;
        $data[]   = (float)($model->nilai_akhir ?? 0);
    }

    $labelsJson = json_encode($labels, JSON_UNESCAPED_UNICODE);
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
                y: { beginAtZero: true, max: 5 }
            }
        }
    });
    JS;

    $this->registerJs($js);
?>
