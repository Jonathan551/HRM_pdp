<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPenilaian $model */
/** @var app\models\DetailPenilaian[] $detailModels */

$this->title = 'Tambah Penilaian';
$this->params['breadcrumbs'][] = ['label' => 'Master Penilaian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-penilaian-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'detailModels' => $detailModels
    ]) ?>

</div>
