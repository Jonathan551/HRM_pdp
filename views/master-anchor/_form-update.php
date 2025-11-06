<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\MasterKriteria;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchor $model */

$form = ActiveForm::begin([
    'id' => 'master-anchor-single-form',
    'method' => 'post',
]);
?>

<?php if ($model->hasErrors()): ?>
    <?php
        $allErrors = $model->getErrors(); 
        ob_start();
    ?>
    <div style="
        background:#f44336;
        color:#fff;
        padding:15px 20px;
        margin-bottom:20px;
        border-radius:2px;
        box-shadow:0 2px 4px rgba(0,0,0,0.2);
        font-size:13px;
    ">
        <div style="margin-bottom:8px; font-weight:500;">
            Perbaiki kesalahan berikut:
        </div>
        <ul style="margin:0; padding-left:20px;">
            <?php foreach ($allErrors as $fieldErrors): ?>
                <?php foreach ($fieldErrors as $msg): ?>
                    <li><?= Html::encode($msg) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php ob_end_flush(); ?>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">

        <?= $form->field($model, 'id_kriteria')->dropDownList(
            ArrayHelper::map(
                MasterKriteria::find()
                    ->orderBy(['nama_kriteria' => SORT_ASC])
                    ->all(),
                'id_kriteria',
                'nama_kriteria'
            ),
            [
                'prompt' => 'Pilih Kriteria...',
                'disabled' => true, 
            ]
        ); ?>

        <?= $form->field($model, 'level_anchor')->textInput([
            'readonly' => true,
        ]) ?>

        <?= $form->field($model, 'deskripsi')->textarea([
            'rows' => 3,
        ]) ?>

       <?= $form->field($model, 'nilai_anchor')->textInput([
            'type' => 'number',
            'step' => '0.001',
            'max'  => $this->params['maxSkala'] ?? null, 
        ])->hint('Masukkan angka desimal (maksimal 3 angka di belakang koma). Nilai tidak boleh melebihi skala.') ?>

    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    <?= Html::a('Kembali', ['view', 'id_kriteria' => $model->id_kriteria], ['class' => 'btn btn-info']) ?>
</div>

<?php ActiveForm::end(); ?>
