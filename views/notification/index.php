<?php
use yii\helpers\Html;
/** @var array $items */
$this->title = 'Notifikasi';
?>
<h4><?= Html::encode($this->title) ?></h4>
<ul class="list-group">
  <?php foreach ($items as $n): ?>
    <li class="list-group-item d-flex justify-content-between">
      <div>
        <div><strong><?= Html::encode($n['judul']) ?></strong></div>
        <div><?= Html::encode($n['deskripsi']) ?></div>
        <small class="text-muted"><?= date('d-m-Y H:i', strtotime($n['created_at'])) ?> — <?= Html::encode($n['aksi']) ?></small>
      </div>
      <?php if ((int)$n['dibaca'] === 0): ?>
        <?= Html::a('Tandai dibaca', ['read', 'id'=>$n['id']], ['class'=>'btn btn-sm btn-outline-secondary']) ?>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ul>
<p class="mt-2">
  <?= Html::a('Tandai semua dibaca', ['read-all'], ['class'=>'btn btn-sm btn-outline-primary']) ?>
</p>
