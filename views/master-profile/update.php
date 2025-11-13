<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Profil';
?>

<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-info text-center">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
            <p class="card-category">Perbarui informasi profil perusahaan</p>
          </div>
          <div class="card-body">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="text-center mb-4">
              <img 
                id="preview-logo" 
                src="<?= Yii::getAlias('@web/uploads/') . ($model->logo ?: 'default-logo.png') ?>" 
                alt="Logo Preview" 
                style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #ddd;"
              >
            </div>

            <div class="text-center mb-4">
              <label class="btn btn-outline-primary btn-round" for="file-logo-input">
                <i class="material-icons">file_upload</i> Upload Logo
              </label>
              <?= $form->field($model, 'file_logo')
                    ->fileInput([
                      'id' => 'file-logo-input',
                      'style' => 'display:none;',
                      'accept' => 'image/*'
                    ])->label(false) ?>
            </div>

            <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'notelfon')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'alamat')->textInput(['maxlength' => true]) ?>

            <div class="form-group text-center mt-4">
              <?= Html::submitButton('<i class="material-icons">save</i> Simpan', ['class' => 'btn btn-success btn-round']) ?>
              <?= Html::a('<i class="material-icons">arrow_back</i> Kembali', ['index'], ['class' => 'btn btn-secondary btn-round']) ?>
            </div>

            <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$this->registerJs(<<<JS
document.getElementById('file-logo-input').addEventListener('change', function(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('preview-logo').src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});
JS);
?>
