<?php

use app\models\MasterKategori;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterKategorisearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Kategori';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kategori-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Kategori', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id_kategori',
            'nama_kategori',
            'nilai_min',
            'nilai_max',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterKategori $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_kategori' => $model->id_kategori]);
                 }
            ],
        ],
    ]); ?>


</div>
