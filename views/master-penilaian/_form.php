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
                            $detail->id_kriteria
                                ? ArrayHelper::map(
                                    MasterAnchor::find()->where(['id_kriteria' => $detail->id_kriteria])->all(),
                                    'id_anchor',
                                    fn($m) => $m->level_anchor . ' - ' . $m->deskripsi . ' (' . $m->nilai_anchor . ')'
                                  )
                                : [],
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


    $kriteriaOptions = '';
    foreach (MasterKriteria::find()->all() as $k) {
        $kriteriaOptions .= "<option value='{$k->id_kriteria}'>{$k->nama_kriteria}</option>";
    }
    $kriteriaOptionsJs = json_encode($kriteriaOptions);


    $rowIndex = count($detailModels);

    $js = <<<JS
    $(document).on('change', '.id-kriteria', function(){
        var idKriteria = $(this).val();
        var row = $(this).closest('tr');
        var anchorDropdown = row.find('.id-anchor');
        anchorDropdown.html('<option value="">Loading...</option>');

        if(idKriteria){
            $.getJSON('$urlListAnchor', {id_kriteria: idKriteria}, function(data){
                anchorDropdown.empty();
                anchorDropdown.append('<option value="">Pilih Anchor</option>');
                $.each(data, function(key, value){
                    anchorDropdown.append('<option value="' + key + '">' + value + '</option>');
                });
            });
        } else {
            anchorDropdown.html('<option value="">Pilih Anchor</option>');
        }
    });

    var rowIndex = $rowIndex;
    var kriteriaOptions = $kriteriaOptionsJs;

  
    $('#add-row').on('click', function(){
        var newRow = `<tr>
            <td>
                <input type="hidden" name="DetailPenilaian[\${rowIndex}][id_detailpenilaian]" value="">
                <select class="form-control id-kriteria" name="DetailPenilaian[\${rowIndex}][id_kriteria]">
                    <option value="">Pilih Kriteria</option>
                    \${kriteriaOptions}
                </select>
            </td>
            <td>
                <select class="form-control id-anchor" name="DetailPenilaian[\${rowIndex}][id_anchor]">
                    <option value="">Pilih Anchor</option>
                </select>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">-</button>
            </td>
        </tr>`;
        $('#detail-table tbody').append(newRow);
        rowIndex++;
    });

    // Hapus baris
    $(document).on('click', '.remove-row', function(){
        $(this).closest('tr').remove();
    });
    JS;

    $this->registerJs($js);
?>
