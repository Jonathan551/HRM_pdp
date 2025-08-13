<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterJabatan $model */

$this->title = 'Create Master Jabatan';
$this->params['breadcrumbs'][] = ['label' => 'Master Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-jabatan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
