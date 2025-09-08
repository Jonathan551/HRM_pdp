<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\MasterKriteria;
use app\models\MasterAnchor;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\DetailPenilaian[] $detailModels */
?>

<div class="master-penilaian-form">

    <?php $form = ActiveForm::begin([
        'id' => 'master-penilaian-form',
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'id_users')->dropDownList(
        ArrayHelper::map(User::find()->all(), 'id_users', 'nama'),
        ['prompt' => 'Pilih Karyawan']
    )->label('Nama Karyawan') ?>

    <!-- Periode Awal -->
    <?= $form->field($model, 'periode_awal')->textInput([
        'class' => 'form-control datepicker',
        'placeholder' => 'Pilih tanggal...'
    ]) ?>

    <!-- Periode Akhir -->
    <?= $form->field($model, 'periode_akhir')->textInput([
        'class' => 'form-control datepicker',
        'placeholder' => 'Pilih tanggal...'
    ]) ?>

    <?= $form->field($model, 'presentase_absensi')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <hr>
    <h4>Detail Penilaian</h4>

    <table class="table table-bordered" id="detail-table">
        <thead>
            <tr>
                <th>Kriteria</th>
                <th>Anchor (Nilai)</th>
                <th width="5%"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detailModels as $i => $detail): ?>
                <?php
                    $anchors = [];
                    if ($detail->id_kriteria) {
                        $anchors = ArrayHelper::map(
                            MasterAnchor::find()->where(['id_kriteria' => $detail->id_kriteria])->all(),
                            'id_anchor',
                            fn($m) => $m->level_anchor . ' - ' . $m->deskripsi . ' (' . $m->nilai_anchor . ')'
                        );
                    }
                ?>
                <tr>
                    <td>
                        <?= Html::activeHiddenInput($detail, "[$i]id_detailpenilaian") ?>
                        <?= Html::activeDropDownList(
                            $detail,
                            "[$i]id_kriteria",
                            ArrayHelper::map(MasterKriteria::find()->all(), 'id_kriteria', 'nama_kriteria'),
                            [
                                'class' => 'form-control id-kriteria',
                                'prompt' => 'Pilih Kriteria'
                            ]
                        ) ?>
                    </td>
                    <td>
                        <?= Html::activeDropDownList(
                            $detail,
                            "[$i]id_anchor",
                            $anchors,
                            [
                                'class' => 'form-control id-anchor',
                                'prompt' => 'Pilih Anchor'
                            ]
                        ) ?>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">-</button>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <button type="button" class="btn btn-primary btn-sm" id="add-row">+ Tambah Kriteria</button>

    <div class="form-group mt-3">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
    $this->registerCssFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/flatpickr', [
        'depends' => [\yii\web\JqueryAsset::class]
    ]);

    $this->registerJs("
        flatpickr('.datepicker', {
            dateFormat: 'd-m-Y',
            allowInput: true,
            locale: 'id'
        });
    ");

    $urlListAnchor = \yii\helpers\Url::to(['master-penilaian/list-anchor']);
    $urlGetDepartemen = \yii\helpers\Url::to(['master-penilaian/get-user-departement']);
    $urlListKriteria = \yii\helpers\Url::to(['master-penilaian/list-kriteria']);

    $rowIndex = count($detailModels);

   $js = <<<JS
    var currentDepartemen = null;
    var rowIndex = $rowIndex;

    // Saat pilih user -> ambil departemen -> load kriteria
    $(document).on('change', '#masterpenilaian-id_users', function(){
        var idUser = $(this).val();
        if(idUser){
            $.getJSON('$urlGetDepartemen', {id_user: idUser}, function(data){
                currentDepartemen = data.id_departement;
                $.getJSON('$urlListKriteria', {id_departement: currentDepartemen}, function(kriteria){
                    var options = '<option value="">Pilih Kriteria</option>';
                    $.each(kriteria, function(key, value){
                        options += '<option value=\"'+key+'\">'+value+'</option>';
                    });
                    $('.id-kriteria').html(options);
                    $('.id-anchor').html('<option value=\"\">Pilih Anchor</option>');
                }).fail(function(jqXHR, textStatus, errorThrown){
                    console.error('list-kriteria error:', textStatus, errorThrown);
                });
            }).fail(function(jqXHR, textStatus, errorThrown){
                console.error('get-user-departement error:', textStatus, errorThrown);
            });
        } else {
            currentDepartemen = null;
            $('.id-kriteria').html('<option value=\"\">Pilih Kriteria</option>');
            $('.id-anchor').html('<option value=\"\">Pilih Anchor</option>');
        }
    });

    $(document).on('change', '.id-kriteria', function () {
        var idKriteria = $(this).val();
        var anchorEl = $(this).closest('tr').find('.id-anchor'); 
        anchorEl.html('<option value=\"\">Loading...</option>');

        if (idKriteria) {
            $.getJSON('$urlListAnchor', {id_kriteria: idKriteria}, function (data) {
                console.log('list-anchor response:', data);
                anchorEl.empty().append('<option value=\"\">Pilih Anchor</option>');
                $.each(data, function (key, value) {
                    anchorEl.append($('<option></option>').attr('value', key).text(value));
                });
            }).fail(function(jqXHR, textStatus, errorThrown){
                console.error('list-anchor error:', textStatus, errorThrown);
                anchorEl.html('<option value=\"\">Gagal load anchor</option>');
            });
        } else {
            anchorEl.html('<option value=\"\">Pilih Anchor</option>');
        }
    });


    $('#add-row').on('click', function(){
        if(!currentDepartemen){
            alert('Pilih karyawan dulu!');
            return;
        }
        $.getJSON('$urlListKriteria', {id_departement: currentDepartemen}, function(data){
            var options = '<option value=\"\">Pilih Kriteria</option>';
            $.each(data, function(key, value){
                options += '<option value=\"'+key+'\">'+value+'</option>';
            });

            var newRow = `<tr>
                <td>
                    <input type="hidden" name="DetailPenilaian[\${rowIndex}][id_detailpenilaian]" value="">
                    <select class="form-control id-kriteria" name="DetailPenilaian[\${rowIndex}][id_kriteria]">
                        \${options}
                    </select>
                </td>
                <td>
                    <select class="form-control id-anchor" name="DetailPenilaian[\${rowIndex}][id_anchor]">
                        <option value=\"\">Pilih Anchor</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">-</button>
                </td>
            </tr>`;
            $('#detail-table tbody').append(newRow);
            rowIndex++;
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.error('add-row -> list-kriteria error:', textStatus, errorThrown);
        });
    });

    
    $(document).on('click', '.remove-row', function(){
        $(this).closest('tr').remove();
    });
    JS;

    $this->registerJs($js);
?>
