<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaiansearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-penilaian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_penilaian') ?>

    <?= $form->field($model, 'id_users') ?>

    <?= $form->field($model, 'nilai_akhir') ?>

    <?= $form->field($model, 'periode_awal') ?>

    <?= $form->field($model, 'periode_akhir') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'presentas_absensi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
