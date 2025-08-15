<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MasterKriteria;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchor $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-anchor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_kriteria' )->dropDownList(
        ArrayHelper::map(MasterKriteria::find()->all(), 'id_kriteria', 'nama_kriteria'),
        ['prompt' => 'Pilih Kriteria']
    ) 
    ->label('Kriteria Penilaian') 
    ?>

    <?= $form->field($model, 'level_anchor')->textInput() ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nilai_anchor')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
