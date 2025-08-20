<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\BandingPenilaian $model */

$this->title = $model->id_banding;
$this->params['breadcrumbs'][] = ['label' => 'Banding Penilaians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="banding-penilaian-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_banding' => $model->id_banding], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_banding' => $model->id_banding], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Kembali', ['pengajuan-banding/index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id_banding',
            [
                'attribute' => 'id_penilaian',
                'label' => 'Nomor Laporan Penilaian',
            ],
            [
                'attribute' => 'id_users',
                'label' => 'User',
                'value' => function ($model) {
                    return $model->user ? $model->user->nama : '-';
                },
            ],
            'status',
            [
                'attribute' => 'tanggal_banding',
                'format' => ['date', 'php:d-m-Y'],
            ],
            'alasan:ntext',
        ],
    ]) ?>

</div>
