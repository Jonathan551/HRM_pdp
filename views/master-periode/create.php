<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPeriode $model */

$this->title = 'Buat Periode';
$this->params['breadcrumbs'][] = ['label' => 'Master Periodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-periode-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
