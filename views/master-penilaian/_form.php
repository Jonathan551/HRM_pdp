<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use kartik\select2\Select2;
use app\models\MasterKriteria;
use app\models\MasterAnchor;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\DetailPenilaian[] $detailModels */
?>



<div class="master-penilaian-form">
    
    <?php 
    
    $form = ActiveForm::begin([
        'id' => 'master-penilaian-form',
        'method' => 'post',
    ]); 
        echo $form->errorSummary(
        array_merge([$model], $detailModels),
        ['class' => 'alert alert-danger', 'header' => Yii::t('app', 'Perbaiki kesalahan berikut:')]
    );

    ?>

    <h4 class="mb-2">
        Detail Penilaian
        <small class="text-danger">
            <?= Html::error($model, 'detailModels') /* tampilkan pesan agregat detail */ ?>
        </small>
    </h4>
    
    <?= $form->field($model, 'id_users')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            User::find()
                ->where(['<>', 'id_users', Yii::$app->user->id]) 
                ->all(),
            'id_users',
            'nama'
        ),
        'options' => [
            'placeholder' => 'Pilih Karyawan...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Nama Karyawan'); ?>


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

    <?= $form->field($model, 'presentase_absensi')->textInput([
        'type' => 'number',
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'maxlength' => true,
        'placeholder' => 'Masukkan berupa angka'
    ])->label('Presentase Absensi') ?>
    
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
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js', [
        'depends' => [\yii\web\JqueryAsset::class],
    ]);


    $this->registerJs("
        urlListAnchor = '" . \yii\helpers\Url::to(['master-penilaian/list-anchor']) . "';
        urlGetDepartemen = '" . \yii\helpers\Url::to(['master-penilaian/get-user-departement']) . "';
        urlListKriteria = '" . \yii\helpers\Url::to(['master-penilaian/list-kriteria']) . "';
        rowIndex = " . count($detailModels) . ";
    ", View::POS_HEAD);


    $this->registerJsFile('@web/js/master-penilaian.js', [
        'depends' => [\yii\web\JqueryAsset::class],
    ]);
?>
