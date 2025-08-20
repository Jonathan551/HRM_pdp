<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BandingPenilaian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="banding-penilaian-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alasan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'id_users')->hiddenInput([
        'value' => Yii::$app->user->id
    ])->label(false) ?>

    <?= $form->field($model, 'status')->hiddenInput([
        'value' => 'Review'
    ])->label(false) ?>

    <?= $form->field($model, 'tanggal_banding')->hiddenInput([
        'value' => date('Y-m-d H:i:s')
    ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Ajukan Banding', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', Yii::$app->request->referrer, ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
