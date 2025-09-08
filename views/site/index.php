<?php
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\helpers\Json;

/** @var float $avgNilai */      
/** @var array $dataKategori */  
/** @var app\models\MasterPenilaian[] $latestPenilaian */
/** @var app\models\BandingPenilaian[] $latestBanding */

$this->registerCssFile('@web/css/dashboard.css');
$this->registerCssFile('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js');
$this->registerJsFile('@web/js/dashboard.js', ['depends' => [JqueryAsset::class]]);


$avg = round((float)($avgNilai ?? 0), 2);
$this->registerJs('initDashboardCharts('
    . Json::htmlEncode($avg) . ', '
    . Json::htmlEncode($dataKategori ?? [])
    . ');');
$this->registerJsVar('AVG_NILAI', $avg);
$this->registerJsVar('DATA_KATEGORI', $dataKategori); 
$fmt = Yii::$app->formatter;
?>


<div class="stats-grid mb-4">
  <a class="link-unstyled" href="<?= Url::to(['/master-kriteria/index']) ?>">
    <div class="md-card">
      <div class="icon"><span class="material-symbols">list_alt</span></div>
      <div class="title">Master Kriteria</div>
      <div class="value"><?= $fmt->asInteger($kriteriaCount) ?></div>
      <div class="sub">Total kriteria terdaftar</div>
    </div>
  </a>

  <a class="link-unstyled" href="<?= Url::to(['/user/index']) ?>">
    <div class="md-card">
      <div class="icon"><span class="material-symbols">group</span></div>
      <div class="title">User</div>
      <div class="value"><?= $fmt->asInteger($userCount) ?></div>
      <div class="sub">Akun aktif</div>
    </div>
  </a>

  <a class="link-unstyled" href="<?= Url::to(['/banding-penilaian/index']) ?>">
    <div class="md-card">
      <div class="icon"><span class="material-symbols">compare_arrows</span></div>
      <div class="title">Banding</div>
      <div class="value"><?= $fmt->asInteger($bandingCount) ?></div>
      <div class="sub">Jumlah pengajuan banding</div>
    </div>
  </a>

  <a class="link-unstyled" href="<?= Url::to(['/master-event/index']) ?>">
    <div class="md-card">
      <div class="icon"><span class="material-symbols">event</span></div>
      <div class="title">Event</div>
      <div class="value"><?= $fmt->asInteger($eventCount) ?></div>
      <div class="sub">Agenda terjadwal</div>
    </div>
  </a>
</div>


<div class="charts-grid mt-5">
  <div class="chart-card">
    <h5>Rata-rata Nilai Akhir</h5>
    <canvas id="avgChart"></canvas>
  </div>
  <div class="chart-card">
    <h5>Distribusi Kategori Penilaian</h5>
    <canvas id="kategoriChart"></canvas>
  </div>
</div>


<div class="lists-grid mt-6">
  <div class="list-card">
    <div class="list-header">Master Penilaian Terbaru</div>
    <ul class="list">
        <?php foreach ($latestPenilaian as $p): ?>
            <li>
            <a href="<?= Url::to([
                    '/master-penilaian/view',
                    'id_penilaian' => $p->id_penilaian   
                ]) ?>">
                <span class="title">#<?= $p->id_penilaian ?></span>
                <span class="meta"><?= $p->user->nama ?? 'Tanpa Nama' ?></span>
                <span class="date">-</span> 
            </a>
            </li>
        <?php endforeach; ?>
        <?php if (empty($latestPenilaian)): ?>
            <li class="empty">Belum ada penilaian.</li>
        <?php endif; ?>
    </ul>
  </div>


  <div class="list-card">
    <div class="list-header">Banding Penilaian Terbaru</div>
    <ul class="list">
       <?php foreach ($latestBanding as $b): ?>
            <li>
                <a href="<?= Url::to([
                    '/banding-penilaian/banding',
                    'id_banding' => $b->id_banding,   
                ]) ?>">
                <span class="title">#<?= $b->id_banding ?></span>
                <span class="meta"><?= $b->user->nama ?? 'Tanpa Nama' ?></span>
                </a>
            </li>
        <?php endforeach; ?>
        <?php if (empty($latestBanding)): ?>
            <li class="empty">Belum ada banding.</li>
        <?php endif; ?>
    </ul>
  </div>
</div>
