<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterEvent $model */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/event-view.css', [
    'depends' => [\yii\bootstrap5\BootstrapAsset::class],
]);

$dt = 'php:d M Y H:i:s';
$da = 'php:d M Y ';  
$fmt     = Yii::$app->formatter;
$webroot = Yii::getAlias('@webroot');
$web     = Yii::getAlias('@web');

$encodePath = function (string $p): string {
    $p = str_replace('\\', '/', $p);
    $parts = array_filter(explode('/', $p), 'strlen');
    $parts = array_map('rawurlencode', $parts);
    return implode('/', $parts);
};

$avatarUrl = $web . '/images/no-avatar.jpeg';
if ($model->user ?? false) {
    $foto = trim((string)$model->user->foto);
    if ($foto !== '') {
        if (preg_match('~^https?://~i', $foto)) {
            $avatarUrl = $foto;
        } else {
            $enc = $encodePath($foto);
            if (is_file($webroot . '/uploads/users/' . $enc)) {
                $avatarUrl = $web . '/uploads/users/' . $enc;
            }
        }
    }
}

$gambarUrl = null;
$g = trim((string)$model->gambar);
if ($g !== '') {
    if (preg_match('~^https?://~i', $g)) {
        $gambarUrl = $g;
    } else {
        $enc = $encodePath($g);
        if (is_file($webroot . '/uploads/' . $enc)) {
            $gambarUrl = $web . '/uploads/' . $enc;
        }
    }
}

$sevClass = [
  'critical' => 'severity-critical',
  'high'     => 'severity-high',
  'medium'   => 'severity-medium',
  'low'      => 'severity-low',
][strtolower((string)$model->severity)] ?? 'severity-default';

$statusClass = [
  'open'    => 'status-open',
  'review'  => 'status-review',
  'closed'  => 'status-closed',
  'selesai' => 'status-closed',
][strtolower((string)$model->status)] ?? 'status-default';
?>

<div class="event-view-container">
  <div class="event-header">
    <div class="header-content">
      <div class="title-section">
        <h1 class="event-title"><?= Html::encode($this->title) ?></h1>
        <span class="event-id">#<?= Html::encode($model->id_event) ?></span>
      </div>
      <p class="event-subtitle">Detail kejadian dari lapangan</p>
    </div>

    <div class="header-actions">
      <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['update', 'id_event' => $model->id_event], [
        'class' => 'btn btn-primary',  
        'escape' => false,
      ]) ?>
      <?= Html::a('<i class="bi bi-arrow-left"></i> Kembali', ['index'], [
        'class' => 'btn-action btn-back',   
        'escape' => false,
      ]) ?>
    </div>
  </div>

  <div class="event-card">
    <div class="user-section">
      <div class="user-info">
        <img src="<?= Html::encode($avatarUrl) ?>" class="user-avatar" alt="User Avatar">
        <div class="user-details">
          <h3 class="user-name"><?= Html::encode($model->created_by ?: ('User #'.$model->id_users)) ?></h3>
          <div class="event-meta">
            <span class="meta-item"><i class="bi bi-clock"></i> <?= $fmt->asRelativeTime($model->tanggal) ?></span>
            <?php if ($model->jenis_event): ?>
              <span class="meta-divider">•</span>
              <span class="meta-item"><i class="bi bi-tag"></i> <?= Html::encode($model->jenis_event) ?></span>
            <?php endif; ?>
            <?php if ($model->lokasi): ?>
              <span class="meta-divider">•</span>
              <span class="meta-item"><i class="bi bi-geo-alt"></i> <?= Html::encode($model->lokasi) ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="badge-group">
        <span class="event-badge <?= $sevClass ?>"><?= strtoupper($model->severity ?: 'LOW') ?></span>
        <span class="event-badge <?= $statusClass ?>"><?= strtoupper($model->status ?: 'OPEN') ?></span>
      </div>
    </div>

    <div class="description-section">
      <h4 class="section-title">Deskripsi</h4>
      <div class="description-content">
        <?= $model->deskripsi ? nl2br(Html::encode($model->deskripsi)) : '<span class="text-empty">Tidak ada deskripsi.</span>' ?>
      </div>
    </div>

    <?php if ($gambarUrl): ?>
      <div class="image-section">
        <h4 class="section-title">Dokumentasi</h4>
        <div class="image-wrapper">
          <?= Html::a(
            Html::img($gambarUrl, ['class' => 'event-image', 'alt' => 'Dokumentasi Event', 'loading' => 'lazy']),
            $gambarUrl,
            ['target' => '_blank', 'rel' => 'noopener', 'class' => 'image-link']
          ) ?>
          <div class="image-overlay"><i class="bi bi-zoom-in"></i><span>Klik untuk memperbesar</span></div>
        </div>
      </div>
    <?php endif; ?>

    <div class="details-section">
      <h4 class="section-title">Informasi Detail</h4>
      <div class="details-grid">
        <div class="detail-item">
          <div class="detail-label"><i class="bi bi-calendar-event"></i> Tanggal Kejadian</div>
          <div class="detail-value"><?= $fmt->asDate($model->tanggal, $da) ?></div>
        </div>

        <?php if ($model->lokasi): ?>
          <div class="detail-item">
            <div class="detail-label"><i class="bi bi-pin-map"></i> Lokasi</div>
            <div class="detail-value"><?= Html::encode($model->lokasi) ?></div>
          </div>
        <?php endif; ?>

        <div class="detail-item">
          <div class="detail-label"><i class="bi bi-building"></i> Departemen</div>
          <div class="detail-value"><?= Html::encode($model->id_departement) ?></div>
        </div>

        <div class="detail-item">
          <div class="detail-label"><i class="bi bi-arrow-repeat"></i> Terakhir Diperbarui</div>
          <div class="detail-value"><?= $fmt->asDatetime($model->updated_at, $dt) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
