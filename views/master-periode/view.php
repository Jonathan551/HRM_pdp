<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterPeriode $model */

$this->title = $model->id_periode;
$this->params['breadcrumbs'][] = ['label' => 'Master Periodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-periode-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_periode' => $model->id_periode], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_periode' => $model->id_periode], [
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
            'id_periode',
            'nama',
            'tanggal_mulai',
            'tanggal_selesai',
            'id_user',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
