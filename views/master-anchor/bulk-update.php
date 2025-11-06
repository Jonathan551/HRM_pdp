<?php
/** @var $this yii\web\View */
/** @var $kriteria app\models\MasterKriteria */
/** @var $model app\models\BatchAnchorInput */

use yii\helpers\Html;


$this->params['breadcrumbs'][] = ['label' => 'Master Anchor', 'url' => ['index', 'id_kriteria' => $kriteria->id_kriteria]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-anchor-bulk-update">
    <h3 style="margin-top:0; color:#3C4858; font-weight:500;">
        <?= Html::encode($this->title) ?>
    </h3>
    <p class="text-muted" style="margin-top:-8px">
        Kriteria: <strong><?= Html::encode($kriteria->nama_kriteria) ?></strong> — Departemen: <strong><?= Html::encode($kriteria->departement->nama_departement ?? '-') ?></strong>
    </p>

    <?= $this->render('_form-bulk-update', [
        'model'    => $model,
        'kriteria' => $kriteria,
    ]) ?>
</div>
