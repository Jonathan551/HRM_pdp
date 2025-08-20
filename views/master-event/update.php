<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterEvent $model */

$this->title = 'Update Master Event: ' . $model->id_event;
$this->params['breadcrumbs'][] = ['label' => 'Master Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_event, 'url' => ['view', 'id_event' => $model->id_event]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
