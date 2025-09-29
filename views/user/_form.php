<?php
use yii\helpers\Html;
use yii\helpers\Url; 
use yii\helpers\ArrayHelper;
use app\models\MasterJabatan;
use app\models\MasterDepartement;
use yii\bootstrap5\ActiveForm;
use yii\web\View;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Username *')  ?>

    <?= $form->field($model, 'password')->passwordInput([
        'value' => '',
        'placeholder' => 'Jika update tidak ingin mengganti password bisa dikosongi saja',
    ]) ?>

    <?= $form->field($model, 'id_jabatan')->dropDownList(
        ArrayHelper::map(MasterJabatan::find()->orderBy('nama_jabatan')->all(), 'id_jabatan', 'nama_jabatan'),
        ['prompt' => 'Pilih Jabatan', 'id' => 'user-id_jabatan']
    )->label('Jabatan *') ?>

    <?= $form->field($model, 'id_departement')->dropDownList(
        ArrayHelper::map(MasterDepartement::find()->orderBy('nama_departement')->all(), 'id_departement', 'nama_departement'),
        ['prompt' => 'Pilih Departement']
    )->label('Departemen *') ?>

    <?= $form->field($model, 'level_jabatan')->textInput([
        'readonly' => true,
        'id' => 'user-level_jabatan',
        'placeholder' => 'Otomatis dari Jabatan'
    ])->label('Level Jabatan') ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true])->label('Nama *') ?>

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
        'inputmode' => 'numeric',
        'pattern' => '[0-9]*',
        'oninput' => 'this.value = this.value.replace(/[^0-9]/g, "")'
    ]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>

    <?= $form->field($model, 'tanggal_lahir')->textInput([
        'class' => 'form-control datepicker',
        'placeholder' => 'Pilih tanggal...'
    ]) ?>

    <?= $form->field($model, 'jenis_kelamin')->dropDownList(['pria' => 'Pria', 'wanita' => 'Wanita'], ['prompt' => '']) ?>
    <?= $form->field($model, 'golongan')->textInput() ?>

    <?= $form->field($model, 'penilaian_terakhir')->textInput([
        'readonly' => true,
        'placeholder' => 'Auto dari Periode Akhir terbaru',
        'id' => 'user-penilaian_terakhir'
    ])->label('Penilaian Terakhir') ?>

    <?= $form->field($model, 'catatan_khusus')->textInput([
        'maxlength' => true,
        'placeholder' => 'Isi catatan, atau biarkan kosong untuk default'
    ]) ?>

    <?php if ($model->foto): ?>
        <div class="mb-2">
            <img src="<?= Html::encode($model->getFotoUrl()) ?>"
                 style="width:64px;height:64px;object-fit:cover;border-radius:50%;"
                 alt="Preview Foto">
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'fotoFile')->fileInput()->label('Upload Foto (jpg/png/webp, maks 2MB)') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $this->registerCssFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js', [
        'depends' => [\yii\web\JqueryAsset::class],
    ]);

    $urlLevel  = Url::to(['user/get-level-jabatan']);
    $urlLatest = $model->isNewRecord ? null : Url::to(['user/latest-penilaian', 'id_users' => $model->id_users]);

    $this->registerJs(<<<JS
        flatpickr('.datepicker', {
        dateFormat: 'd-m-Y',
        allowInput: true,
        clickOpens: true,
        disableMobile: true
        });

        $('#user-id_jabatan').on('change', function () {
        var id = $(this).val();
        if (!id) { $('#user-level_jabatan').val(''); return; }
        $.getJSON('{$urlLevel}', { id: id }).done(function(res){
            $('#user-level_jabatan').val(res && res.level_jabatan ? res.level_jabatan : '');
        });
        });

        if ($('#user-id_jabatan').val()) {
        $('#user-id_jabatan').trigger('change');
        }

        JS, View::POS_READY);

        if ($urlLatest) {
            $this->registerJs(<<<JS
        $.getJSON('{$urlLatest}').done(function(res){
        if (res && res.penilaian_terakhir) {
            $('#user-penilaian_terakhir').val(res.penilaian_terakhir);
        }
        });
        JS, View::POS_READY);
        }
    ?>

</div>
