<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var app\models\BandingPenilaian $model */

$form = ActiveForm::begin([
    'id' => 'form-banding',
    'action' => ['pengajuan-banding/create', 'id_penilaian' => $model->id_penilaian],
    'method' => 'post',
    'options' => ['data-pjax' => 0],
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
]); ?>

<?= $form->errorSummary($model) ?>

<?= $form->field($model, 'alasan')->textarea([
    'rows' => 6,
    'maxlength' => true,
    'placeholder' => 'Tuliskan alasan...',
]) ?>

<div class="form-group">
    <?= Html::submitButton('Ajukan Banding', [
        'class' => 'btn btn-success',
        'id' => 'btn-submit-banding'
    ]) ?>
    <?= Html::a('Kembali', ['pengajuan-banding/index'], ['class' => 'btn btn-info']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
(function(){
  var form = document.getElementById('form-banding');
  if(!form) return;
  form.addEventListener('submit', function(){
    var btn = document.getElementById('btn-submit-banding');
    if(btn){
      btn.disabled = true;
      btn.innerText = 'Mengirim...';
    }
  });
})();
JS;
$this->registerJs($js);
?>
