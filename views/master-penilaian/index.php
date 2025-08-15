<?php

use app\models\MasterPenilaian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaiansearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Penilaian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-penilaian-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Penilaian', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id_penilaian',
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
                'format' => ['date', 'php:d-m-Y H:i'],
            ],
            [
                'attribute' => 'periode_akhir',
                'label' => 'Periode Awal Akhir',
                'format' => ['date', 'php:d-m-Y H:i'],
            ],
            'status',
            [
                'attribute' => 'presentase_absensi',
                'label' => 'Presentase Absensi',
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterPenilaian $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_penilaian' => $model->id_penilaian]);
                 }
            ],
        ],
    ]); ?>

</div>
