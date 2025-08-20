<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BandingPenilaian $model */

$this->title = 'Update Banding Penilaian: ' . $model->id_banding;
$this->params['breadcrumbs'][] = ['label' => 'Banding Penilaians', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_banding, 'url' => ['view', 'id_banding' => $model->id_banding]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="banding-penilaian-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
