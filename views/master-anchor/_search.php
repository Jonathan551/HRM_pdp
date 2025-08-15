<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchorsearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-anchor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_anchor') ?>

    <?= $form->field($model, 'id_kriteria') ?>

    <?= $form->field($model, 'level_anchor') ?>

    <?= $form->field($model, 'deskripsi') ?>

    <?= $form->field($model, 'nilai_anchor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
