<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchor $model */

$this->title = 'Update Master Anchor: ' . $model->id_anchor;
$this->params['breadcrumbs'][] = ['label' => 'Master Anchors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_anchor, 'url' => ['view', 'id_anchor' => $model->id_anchor]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-anchor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
