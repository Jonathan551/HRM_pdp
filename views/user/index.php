<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Usersearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id_users',
            'username',
            // 'password_hash',
            [
                'attribute' => 'id_jabatan',
                'label' => 'Jabatan',
                'value' => function ($model) {
                    return $model->jabatan ? $model->jabatan->nama_jabatan : '-';
                },
            ],
            [
                'attribute' => 'id_departement',
                'label' => 'Departemen',
                'value' => function ($model) {
                    return $model->departement ? $model->departement->nama_departement : '-';
                },
            ],
            'level_jabatan',
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
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_users' => $model->id_users]);
                 }
            ],
        ],
    ]); ?>


</div>
