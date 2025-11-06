<?php
// File: views/master-event/_form.php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterEvent $model */
/** @var yii\widgets\ActiveForm $form */


$currentUserId = Yii::$app->user->id;
$currentUser   = User::find()->with('departement')->where(['id_users' => $currentUserId])->one();
$displayName   = $currentUser
    ? ($currentUser->nama . ' (' . ($currentUser->departement->nama_departement ?? '-') . ')')
    : 'User';

$isNew = $model->isNewRecord;


if ($isNew) {
    $model->id_users   = $currentUserId;
    $model->created_by = $currentUserId;
}
?>
<div class="master-event-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
    $ownerUser = $isNew ? $currentUser : User::find()->with('departement')->where(['id_users' => $model->id_users])->one();
    $ownerName = $ownerUser
        ? ($ownerUser->nama . ' (' . ($ownerUser->departement->nama_departement ?? '-') . ')')
        : '-';
    ?>
    <div class="form-group">
        <label class="control-label">User</label>
        <?= Html::textInput('user_display', $ownerName, ['class' => 'form-control', 'readonly' => true]) ?>
        <?= $form->field($model, 'id_users')->hiddenInput(['value' => $ownerUser->id_users ?? $currentUserId])->label(false) ?>
    </div>
    <div style="margin-bottom:15px;"></div>

    <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>
    <div style="margin-bottom:15px;"></div>

    <div class="form-group">
        <?= Html::button('Tambah Gambar', ['class' => 'btn btn-primary', 'id' => 'btn-add-file']) ?>
        <?= Html::button('Hapus Gambar', ['class' => 'btn btn-danger', 'id' => 'btn-remove-file', 'style' => 'display:none;']) ?>
    </div>

    <?= $form->field($model, 'uploadFile')->fileInput(['id' => 'event-upload', 'style' => 'display:none;'])->label(false) ?>

    <?php if (!$model->isNewRecord && $model->gambar): ?>
        <div id="existing-image" style="margin-bottom:15px;">
            <p>File lama:</p>
            <img src="<?= Yii::getAlias('@web/uploads/') . $model->gambar ?>" width="200" style="border:1px solid #ccc; padding:5px;">
        </div>
    <?php endif; ?>

    <div id="preview-container" style="display:none; margin-bottom:15px;">
        <p>Preview:</p>
        <img id="preview-image" src="" width="200" style="border:1px solid #ccc; padding:5px;">
    </div>

    <?= $form->field($model, 'tanggal')->textInput([
        'class' => 'form-control datepicker',
        'placeholder' => 'Pilih tanggal...'
    ]) ?>
    <div style="margin-bottom:15px;"></div>

    <?= $form->field($model, 'jenis_event')->textInput(['maxlength' => true]) ?>
    <div style="margin-bottom:15px;"></div>

    <?= $form->field($model, 'severity')->dropDownList([
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'critical' => 'Critical'
    ], ['prompt' => 'Pilih Severity']) ?>
    <div style="margin-bottom:15px;"></div>

    <?= $form->field($model, 'lokasi')->textInput(['maxlength' => true]) ?>
    <div style="margin-bottom:15px;"></div>

    <?= $form->field($model, 'created_by')->hiddenInput(['value' => $currentUserId])->label(false) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'status')->dropDownList(['open' => 'Open']) ?>
    <?php else: ?>
        <?= $form->field($model, 'status')->dropDownList([
            'open' => 'Open',
            'review' => 'Review',
            'closed' => 'Closed'
        ], ['prompt' => 'Pilih Status']) ?>
    <?php endif; ?>
    <div style="margin-bottom:15px;"></div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
        $this->registerCssFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
        $this->registerJsFile('https://cdn.jsdelivr.net/npm/flatpickr', [
            'depends' => [\yii\web\JqueryAsset::class]
        ]);
        $defaultDateJs = $isNew ? "defaultDate: 'today'," : "";
        $this->registerJs("
            flatpickr('.datepicker', {
                dateFormat: 'd-m-Y',
                allowInput: true,
                $defaultDateJs
            });

            const uploadInput = document.getElementById('event-upload');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const btnAdd = document.getElementById('btn-add-file');
            const btnRemove = document.getElementById('btn-remove-file');
            const existingImage = document.getElementById('existing-image');

            btnAdd.addEventListener('click', function() { uploadInput.click(); });

            uploadInput.addEventListener('change', function() {
                const [file] = this.files;
                if (file) {
                    previewImage.src = URL.createObjectURL(file);
                    previewContainer.style.display = 'block';
                    btnRemove.style.display = 'inline-block';
                    if (existingImage) existingImage.style.display = 'none';
                }
            });

            btnRemove.addEventListener('click', function() {
                uploadInput.value = '';
                previewImage.src = '';
                previewContainer.style.display = 'none';
                btnRemove.style.display = 'none';
                if (existingImage) existingImage.style.display = 'block';
            });
        ");
    ?>

</div>
