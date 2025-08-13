<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterDepartement $model */

$this->title = 'Update Master Departement: ' . $model->id_departement;
$this->params['breadcrumbs'][] = ['label' => 'Master Departements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_departement, 'url' => ['view', 'id_departement' => $model->id_departement]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-departement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
