<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterKriteria $model */

$this->title = 'Create Master Kriteria';
$this->params['breadcrumbs'][] = ['label' => 'Master Kriterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kriteria-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
