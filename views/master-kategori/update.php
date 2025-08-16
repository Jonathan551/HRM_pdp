<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterKategori $model */

$this->title = 'Update Master Kategori: ' . $model->id_kategori;
$this->params['breadcrumbs'][] = ['label' => 'Master Kategoris', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_kategori, 'url' => ['view', 'id_kategori' => $model->id_kategori]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-kategori-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
