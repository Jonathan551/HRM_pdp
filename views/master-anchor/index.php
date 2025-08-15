<?php

use app\models\MasterAnchor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchorsearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Anchor';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-anchor-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Anchor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id_anchor',
            [
                'attribute' => 'id_kriteria',
                'label' => 'Kriteria',
                'value' => function ($model) {
                    return $model->kriteria ? $model->kriteria->nama_kriteria : '-';
                },
            ],
            'level_anchor',
            'deskripsi:ntext',
            'nilai_anchor',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterAnchor $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_anchor' => $model->id_anchor]);
                 }
            ],
        ],
    ]); ?>


</div>
