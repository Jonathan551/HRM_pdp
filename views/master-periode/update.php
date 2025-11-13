<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPeriode $model */

$this->title = 'Update Master Periode: ' . $model->id_periode;
$this->params['breadcrumbs'][] = ['label' => 'Master Periodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_periode, 'url' => ['view', 'id_periode' => $model->id_periode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-periode-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
