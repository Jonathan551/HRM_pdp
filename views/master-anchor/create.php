<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchor $model */

$this->title = 'Create Master Anchor';
$this->params['breadcrumbs'][] = ['label' => 'Master Anchors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-anchor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
