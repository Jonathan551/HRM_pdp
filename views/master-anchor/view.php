<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchor $model */

$this->title = $model->id_anchor;
$this->params['breadcrumbs'][] = ['label' => 'Master Anchors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-anchor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_anchor' => $model->id_anchor], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_anchor' => $model->id_anchor], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Input', ['create'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id_anchor',
            [
                'attribute' => 'id_kriteria',
                'value' => function ($model) {
                    return $model->kriteria ? $model->kriteria->nama_kriteria : '-';
                },
            ],
            'level_anchor',
            'deskripsi:ntext',
            'nilai_anchor',
        ],
    ]) ?>

</div>
