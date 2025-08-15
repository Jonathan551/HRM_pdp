<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterKriteriasearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-kriteria-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_kriteria') ?>

    <?= $form->field($model, 'id_departement') ?>

    <?= $form->field($model, 'nama_kriteria') ?>

    <?= $form->field($model, 'deskripsi') ?>

    <?= $form->field($model, 'bobot') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
