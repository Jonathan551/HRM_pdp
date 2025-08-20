<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BandingPenilaiansearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="banding-penilaian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_banding') ?>

    <?= $form->field($model, 'id_penilaian') ?>

    <?= $form->field($model, 'id_users') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'tanggal_banding') ?>

    <?php // echo $form->field($model, 'alasan') ?>

    <?php // echo $form->field($model, 'review') ?>

    <?php // echo $form->field($model, 'tanggal_review') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
