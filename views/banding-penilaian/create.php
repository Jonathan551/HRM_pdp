<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BandingPenilaian $model */

$this->title = 'Form Banding Penilaian';
$this->params['breadcrumbs'][] = ['label' => 'Banding Penilaians', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banding-penilaian-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
