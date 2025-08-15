<?php

use app\models\MasterKriteria;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterKriteriasearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Kriteria';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kriteria-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Kriteria', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_departement',
                'label' => 'Departemen',
                'value' => function ($model) {
                    return $model->departement ? $model->departement->nama_departement : '-';
                },
            ],
            'nama_kriteria',
            'deskripsi:ntext',
            'bobot',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterKriteria $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_kriteria' => $model->id_kriteria]);
                 }
            ],
        ],
    ]); ?>


</div>
