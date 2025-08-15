<?php

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

    <?= $form->field($model, 'id_departement')->dropDownList(
        ArrayHelper::map(MasterDepartement::find()->all(), 'id_departement', 'nama_departement'),
        ['prompt' => 'Pilih Departement']
    ) ?>

    <?= $form->field($model, 'nama_kriteria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bobot')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', Yii::$app->request->referrer ?: ['index'], [
                'class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
