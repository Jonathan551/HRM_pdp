<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\MasterJabatan;
use app\models\MasterDepartement;
use yii\bootstrap5\ActiveForm;


/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['value' => '']) ?>

    <?= $form->field($model, 'id_jabatan')->dropDownList(
        ArrayHelper::map(MasterJabatan::find()->all(), 'id_jabatan', 'nama_jabatan'),
        ['prompt' => 'Pilih Jabatan']
    )->label('Jabatan') 
    ?>
    
    <?= $form->field($model, 'id_departement')->dropDownList(
        ArrayHelper::map(MasterDepartement::find()->all(), 'id_departement', 'nama_departement'),
        ['prompt' => 'Pilih Departement']
    ) ->label('Departemen') 
    ?>

    <?= $form->field($model, 'level_jabatan')->dropDownList(
        ArrayHelper::map(MasterJabatan::find()->all(), 'level_jabatan', 'level_jabatan'),
        ['prompt' => 'Pilih Level Jabatan']
    ) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_masuk')->textInput([
        'class' => 'form-control datepicker',
        'placeholder' => 'Pilih tanggal...'
    ]) ?>

    <?= $form->field($model, 'pendidikan_terakhir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_karyawan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lokasi_kerja')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'atasan_langsung')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_hp')->textInput([
        'maxlength' => true,
        'type' => 'number',
        'min' => '0',
        'oninput' => 'this.value = this.value.replace(/[^0-9]/g, "")'
    ]) ?>

    
    <?= $form->field($model, 'email')->textInput([
        'maxlength' => true,
        'type' => 'email'
    ]) ?>

    <?= $form->field($model, 'tanggal_lahir')->textInput([
        'class' => 'form-control datepicker',
        'placeholder' => 'Pilih tanggal...'
    ]) ?>

    <?= $form->field($model, 'jenis_kelamin')->dropDownList([ 'pria' => 'Pria', 'wanita' => 'Wanita', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'golongan')->textInput() ?>

    <?= $form->field($model, 'penilaian_terakhir')->textInput([
        'class' => 'form-control datepicker',
        'placeholder' => 'Pilih tanggal...'
    ]) ?>

    <?= $form->field($model, 'catatan_khusus')->textInput([
        'maxlength' => true,
        'placeholder' => 'Isi catatan, atau biarkan kosong untuk default'
    ]) ?>

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

        $this->registerJs("
            flatpickr('.datepicker', {
                dateFormat: 'd-m-Y',
                allowInput: true
            });
        ");
    ?>

</div>
