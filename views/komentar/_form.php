<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
/** @var \app\models\Komentar $model */

$this->registerCssFile('@web/css/komentar.css', [
    'depends' => [\yii\bootstrap5\BootstrapAsset::class],
]);

$form = ActiveForm::begin([
    'action'  => ['komentar/create', 'id_event' => $model->id_event],
    'options' => ['data-turbo' => 'false'],
]);
?>

<div class="komentar-main">
  <ul class="nav nav-tabs komentar-tabs" role="tablist">
    <li class="nav-item">
      <button type="button" class="nav-link active" data-role="tab-write">Write</button>
    </li>
    <li class="nav-item">
      <button type="button" class="nav-link" data-role="tab-preview">Preview</button>
    </li>
  </ul>


  <div class="komentar-body">
    <div class="komentar-write">
      <?= $form->field($model, 'id_event')->hiddenInput()->label(false) ?>
      <?= $form->field($model, 'deskripsi')->textarea([
          'rows' => 6,
          'id' => 'komentar-deskripsi',
          'placeholder' => 'Add your answer here...',
      ])->label(false) ?>
    </div>

    <div class="komentar-preview d-none" id="komentar-preview">
      <div class="text-muted small">Nothing to preview.</div>
    </div>
  </div>

  <div class="d-flex gap-2 mt-3">
    <?= Html::submitButton('Buat', ['class' => 'btn btn-primary']) ?>
    <?= Html::button('Cancel', ['class' => 'btn btn-outline-secondary', 'id' => 'btn-cancel-comment']) ?>
  </div>
</div>

<?php ActiveForm::end(); ?>