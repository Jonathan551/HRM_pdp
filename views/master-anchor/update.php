<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterAnchor $model */

$this->title = 'Update Master Anchor: Level ' . $model->level_anchor;
$this->params['breadcrumbs'][] = ['label' => 'Master Anchors', 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => 'Kriteria ' . ($model->kriteria ? $model->kriteria->nama_kriteria : $model->id_kriteria),
    'url'   => ['view', 'id_kriteria' => $model->id_kriteria],
];
$this->params['breadcrumbs'][] = 'Update';


$maxSkala = \app\models\MasterAnchor::find()
    ->where(['id_kriteria' => $model->id_kriteria])
    ->max('level_anchor');

$this->params['maxSkala'] = $maxSkala;
?>

<div class="master-anchor-update">

    <h3 style="margin-top:0; color:#3C4858; font-weight:500;">
        <?= Html::encode($this->title) ?>
    </h3>

    <?= $this->render('_form-update', [
        'model' => $model,
    ]) ?>

</div>