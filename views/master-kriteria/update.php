<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterKriteria $model */

$this->title = 'Update Master Kriteria: ' . $model->id_kriteria;
$this->params['breadcrumbs'][] = ['label' => 'Master Kriterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_kriteria, 'url' => ['view', 'id_kriteria' => $model->id_kriteria]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-kriteria-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
