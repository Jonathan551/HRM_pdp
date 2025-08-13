<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterJabatan $model */

$this->title = 'Update Master Jabatan: ' . $model->id_jabatan;
$this->params['breadcrumbs'][] = ['label' => 'Master Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_jabatan, 'url' => ['view', 'id_jabatan' => $model->id_jabatan]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-jabatan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
