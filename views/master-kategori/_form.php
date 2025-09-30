<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterKategori $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-kategori-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_kategori')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nilai_min')->textInput([
        'type' => 'number',
        'step' => 'any',
        'maxlength' => true,
        'placeholder' => 'Masukkan berupa angka'
    ])->label('Nilai Minimum') ?>

    <?= $form->field($model, 'nilai_max')->textInput([
        'type' => 'number',
        'step' => 'any',
        'maxlength' => true,
        'placeholder' => 'Masukkan berupa angka'
    ])->label('Nilai Maximum') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
