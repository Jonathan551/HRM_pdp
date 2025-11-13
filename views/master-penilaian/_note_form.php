<?php
// views/master-penilaian/_note_form.php — form minimal
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var \app\models\MasterPenilaian $model */
$this->title = 'Catatan & Rekomendasi — #'.$model->id_penilaian;
?>
<h3><?= Html::encode($this->title) ?></h3>

<div class="panel panel-default" style="padding:15px;">
  <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'catatan')->textInput(['maxlength'=>true]) ?>
    <?= $form->field($model, 'rekomendasi')->textarea(['rows'=>5]) ?>

    <div class="checkbox" style="margin-top:10px;margin-bottom:15px;">
      <label>
        <input type="checkbox" name="send_email" value="1" checked>
        Kirim email hasil penilaian ke karyawan
      </label>
    </div>

    <div class="form-group">
      <?= Html::submitButton('Simpan', ['class'=>'btn btn-primary']) ?>
      <?= Html::a('Batal', ['index'], ['class'=>'btn btn-default']) ?>
    </div>
  <?php ActiveForm::end(); ?>
</div>
