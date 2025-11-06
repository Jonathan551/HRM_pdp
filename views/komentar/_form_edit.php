<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
/** @var \app\models\Komentar $model */
$form = ActiveForm::begin([
    'action'  => ['komentar/update', 'id' => $model->id_komentar],
    'options' => ['class' => 'comment-edit-form', 'data-id' => $model->id_komentar],
]);
?>
<?= $form->field($model, 'id_event')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'deskripsi')->textarea(['rows' => 5])->label(false) ?>
<div class="d-flex gap-2">
  <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
  <?= Html::button('Cancel', ['class' => 'btn btn-outline-secondary', 'data-cancel-edit' => $model->id_komentar]) ?>
</div>
<?php ActiveForm::end(); ?>

