<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterEvent $model */

$this->title = $model->id_event;
$this->params['breadcrumbs'][] = ['label' => 'Master Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_event' => $model->id_event], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_event' => $model->id_event], [
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
            // Ubah label jadi "User"
            [
                'attribute' => 'id_users',
                'label' => 'User',
                'value' => $model->users ? $model->users->nama : '-', 
            ],

            'judul',
            'deskripsi:ntext',
            [
                'attribute' => 'gambar',
                'label' => 'Preview Gambar',
                'format' => 'html',
                'value' => $model->gambar 
                    ? Html::img(Yii::getAlias('@web/uploads/') . $model->gambar, ['width' => '200'])
                    : '(tidak ada gambar)',
            ],
            [
                'attribute' => 'tanggal',
                'format' => ['date', 'php:d-m-Y'],
            ],
            'jenis_event',
            'severity',
            'lokasi',
            [
                'attribute' => 'id_departement',
                'label' => 'Departemen',
                'value' => $model->departement ? $model->departement->nama_departement : '-',
            ],

            [
                'attribute' => 'created_by',
                'label' => 'Dibuat Oleh',
                'value' => $model->users ? $model->users->nama : '-', 
            ],

            'status',
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d-m-Y'],
            ],
        ],
    ]) ?>

</div>
