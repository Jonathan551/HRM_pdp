<?php

use app\models\MasterDepartement;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterDepartementsearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Departement';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-departement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Departement', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nama_departement',
            'deskripsi:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterDepartement $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_departement' => $model->id_departement]);
                 }
            ],
        ],
    ]); ?>


</div>
