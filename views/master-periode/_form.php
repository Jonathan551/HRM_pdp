<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
/** @var \app\models\MasterPeriode $model */
?>
<div class="master-periode-form">
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'nama')->textInput(['maxlength'=>true]) ?>
    <?= $form->field($model, 'tanggal_mulai')->input('date') ?>
    <?= $form->field($model, 'tanggal_selesai')->input('date') ?>
    <?= $form->field($model, 'status')->dropDownList(['Terbuka'=>'Terbuka','Tertutup'=>'Tertutup']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Buat Periode' : 'Simpan', ['class'=>'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>