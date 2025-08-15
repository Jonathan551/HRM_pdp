<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Usersearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_users') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'access_token') ?>

    <?php // echo $form->field($model, 'id_jabatan') ?>

    <?php // echo $form->field($model, 'id_departement') ?>

    <?php // echo $form->field($model, 'level_jabatan') ?>

    <?php // echo $form->field($model, 'nama') ?>

    <?php // echo $form->field($model, 'tanggal_masuk') ?>

    <?php // echo $form->field($model, 'pendidikan_terakhir') ?>

    <?php // echo $form->field($model, 'status_karyawan') ?>

    <?php // echo $form->field($model, 'lokasi_kerja') ?>

    <?php // echo $form->field($model, 'atasan_langsung') ?>

    <?php // echo $form->field($model, 'nomor_hp') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'tanggal_lahir') ?>

    <?php // echo $form->field($model, 'jenis_kelamin') ?>

    <?php // echo $form->field($model, 'golongan') ?>

    <?php // echo $form->field($model, 'penilaian_terakhir') ?>

    <?php // echo $form->field($model, 'catatan_khusus') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
