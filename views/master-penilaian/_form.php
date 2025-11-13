<?php
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use kartik\select2\Select2;
use app\models\MasterKriteria;
use app\models\MasterAnchor;
use app\models\MasterPeriode; // <<< tambah

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\DetailPenilaian[] $detailModels */

$isFirstFill = !$model->getDetailPenilaian()->exists() && $model->nilai_akhir === null;
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
        <small class="text-danger">
            <?= Html::error($model, 'detailModels') ?>
        </small>
    </h4>

    <?php
    $periodeData = ArrayHelper::map(
        MasterPeriode::find()->orderBy(['tanggal_mulai' => SORT_DESC])->all(),
        'id_periode',
        function ($p) {
            return trim(sprintf('%s (%s s/d %s)', $p->nama, $p->tanggal_mulai, $p->tanggal_selesai));
        }
    );
    ?>

    <?= $form->field($model, 'id_periode')->widget(Select2::class, [
        'data' => $periodeData,
        'options' => [
            'placeholder' => 'Pilih Periode...',
            'disabled' => !$model->isNewRecord,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Periode'); ?>

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
            'disabled' => !$model->isNewRecord,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Nama Karyawan'); ?>

    <?= $form->field($model, 'presentase_absensi')->textInput([
        'type' => 'number',
        'min' => 0,
        'max' => 100,
        'step' => 1,
        'maxlength' => true,
        'placeholder' => 'Masukkan berupa angka'
    ])->label('Presentase Absensi') ?>
    
    <?php if (!$isFirstFill): ?>
        <?= $form->field($model, 'catatan')->textInput(['maxlength'=>true]) ?>
        <?= $form->field($model, 'rekomendasi')->textarea(['rows'=>4,'placeholder'=>'Tulis rekomendasi dari owner...'])->label('Rekomendasi') ?>
    <?php endif; ?>

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
