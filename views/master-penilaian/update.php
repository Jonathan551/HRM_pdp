<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */
/** @var app\models\DetailPenilaian[] $detailModels */

$this->title = 'Update Penilaian: ' . $model->id_penilaian;
$this->params['breadcrumbs'][] = ['label' => 'Master Penilaian', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_penilaian, 'url' => ['view', 'id_penilaian' => $model->id_penilaian]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-penilaian-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'detailModels' => $detailModels
    ]) ?>

</div>
