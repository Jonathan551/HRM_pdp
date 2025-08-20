<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterEventsearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_event') ?>

    <?= $form->field($model, 'id_users') ?>

    <?= $form->field($model, 'judul') ?>

    <?= $form->field($model, 'deskripsi') ?>

    <?= $form->field($model, 'gambar') ?>

    <?php // echo $form->field($model, 'tanggal') ?>

    <?php // echo $form->field($model, 'jenis_event') ?>

    <?php // echo $form->field($model, 'severity') ?>

    <?php // echo $form->field($model, 'lokasi') ?>

    <?php // echo $form->field($model, 'id_departemen') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
