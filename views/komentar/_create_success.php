<?php
use yii\helpers\Html;
/** @var \app\models\Komentar $model */
?>

<div class="alert alert-success">
  Komentar tersimpan. <?= Html::a('Muat ulang halaman', ['master-event/view', 'id_event' => $model->id_event], ['class' => 'alert-link']) ?>
</div>