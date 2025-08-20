<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterEvent $model */

$this->title = 'Create  Event';
$this->params['breadcrumbs'][] = ['label' => 'Event', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
