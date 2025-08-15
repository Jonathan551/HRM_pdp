<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterKriteria $model */

$this->title = $model->id_kriteria;
$this->params['breadcrumbs'][] = ['label' => 'Master Kriterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-kriteria-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_kriteria' => $model->id_kriteria], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_kriteria' => $model->id_kriteria], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
             [
                'attribute' => 'id_departement',
                'value' => function ($model) {
                    return $model->departement ? $model->departement->nama_departement : '-';
                },
            ],
            'nama_kriteria',
            'deskripsi:ntext',
            'bobot',
        ],
    ]) ?>

</div>
