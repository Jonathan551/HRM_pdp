<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterDepartement $model */

$this->title = $model->id_departement;
$this->params['breadcrumbs'][] = ['label' => 'Master Departements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-departement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_departement' => $model->id_departement], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_departement' => $model->id_departement], [
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
            'nama_departement',
            'deskripsi:ntext',
        ],
    ]) ?>

</div>
