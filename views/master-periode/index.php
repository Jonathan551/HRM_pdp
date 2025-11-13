<?php

use app\models\MasterPeriode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterPeriodesearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Periode';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-periode-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Periode', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nama',
            [
                'attribute' => 'tanggal_mulai',
                'format' => ['date', 'php:d-m-Y'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => ['date', 'php:d-m-Y'],
            ],
            'status',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterPeriode $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_periode' => $model->id_periode]);
                 }
            ],
        ],
    ]); ?>


</div>
