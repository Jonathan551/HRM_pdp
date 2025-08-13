<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterDepartement $model */

$this->title = 'Create Master Departement';
$this->params['breadcrumbs'][] = ['label' => 'Master Departements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-departement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
