<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\MasterKriteria;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchor $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-anchor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_kriteria')->widget(Select2::class,[
        'data' => ArrayHelper::map(MasterKriteria::find()->all(),'id_kriteria', 'nama_kriteria'),
        'options' => [
            'Pilih Kriteria...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ->label('Nama Kriteria');?>

    <?= $form->field($model, 'level_anchor')->textInput() ->label('Skala') ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nilai_anchor')->textInput([
        'type' => 'number',
        'min' => 0,
        'step' => 1,
        'maxlength' => true,
        'placeholder' => 'Masukkan berupa angka'
    ])->label('Nilai Anchor') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
