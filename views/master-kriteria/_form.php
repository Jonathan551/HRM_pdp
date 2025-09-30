<?php

use app\models\MasterKriteria;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MasterDepartement;

/** @var yii\web\View $this */
/** @var app\models\MasterKriteria $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-kriteria-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_departement')->widget(Select2::class,[
        'data' => ArrayHelper::map(MasterDepartement::find()->all(),'id_departement', 'nama_departement'),
        'options' => [
            'Pilih Departemen...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ->label('Nama Departemen');?>

    <?= $form->field($model, 'nama_kriteria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bobot')->textInput([
        'type' => 'number',
        'min' => 0,
        'step' => 1,
        'maxlength' => true,
        'placeholder' => 'Masukkan berupa angka'
    ])->label('Bobot') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', Yii::$app->request->referrer ?: ['index'], [
                'class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
