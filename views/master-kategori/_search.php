<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterKategorisearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-kategori-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_kategori') ?>

    <?= $form->field($model, 'nama_kategori') ?>

    <?= $form->field($model, 'nilai_min') ?>

    <?= $form->field($model, 'nilai_max') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
