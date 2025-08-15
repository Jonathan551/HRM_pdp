<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->id_users;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_users' => $model->id_users], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_users' => $model->id_users], [
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
            // 'id_users',
            'username',
            // 'password_hash',
            [
                'attribute' => 'id_jabatan',
                'value' => function ($model) {
                    return $model->jabatan ? $model->jabatan->nama_jabatan : '-';
                },
            ],
            [
                'attribute' => 'id_departement',
                'value' => function ($model) {
                    return $model->departement ? $model->departement->nama_departement : '-';
                },
            ],
            'nama',
            [
                'attribute' => 'tanggal_masuk',
                'format' => ['date', 'php:d-m-Y H:i'],
            ],
            'pendidikan_terakhir',
            'status_karyawan',
            'lokasi_kerja',
            'atasan_langsung',
            'nomor_hp',
            'email:email',
            [
                'attribute' => 'tanggal_lahir',
                'format' => ['date', 'php:d-m-Y'],
            ],
            'jenis_kelamin',
            'golongan',
            [
                'attribute' => 'penilaian_terakhir',
                'format' => ['date', 'php:d-m-Y H:i'],
            ],
            'catatan_khusus',
        ],
    ]) ?>

</div>
