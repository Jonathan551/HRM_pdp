<?php
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\helpers\Json;

\deyraka\materialdashboard\web\MaterialDashboardAsset::register($this);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('@web/js/dashboard.js', ['depends' => [JqueryAsset::class]]);

$avg = round((float)($myAvg ?? 0), 2);
$this->registerJs('initDashboardCharts('
  . Json::htmlEncode($avg) . ', '
  . Json::htmlEncode($myKategori ?? [])
  . ');');
?>

<div class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon"><i class="material-icons">grade</i></div>
            <p class="card-category">Nilai Rata-rata Saya</p>
            <h3 class="card-title"><?= Yii::$app->formatter->asDecimal($avg, 2) ?></h3>
          </div>
          <div class="card-footer">
            <div class="stats">Akumulasi penilaian saya</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-info">
            <h4 class="card-title">Riwayat Nilai Saya (12 Bulan)</h4>
            <p class="card-category">Rata-rata per periode</p>
          </div>
          <div class="card-body">
            <div style="position:relative;height:280px;">
              <canvas id="avgChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-rose">
            <h4 class="card-title">Penilaian Saya Terbaru</h4>
            <p class="card-category">5 periode terakhir</p>
          </div>
          <div class="card-body table-responsive">
            <table class="table">
              <thead class="text-primary">
                <tr>
                  <th>#</th>
                  <th>Periode</th>
                  <th class="text-right">Nilai</th>
                </tr>
              </thead>
              <tbody>
              <?php if (!empty($myLatestPenilaian)): ?>
                <?php foreach ($myLatestPenilaian as $p): ?>
                  <tr>
                    <td><?= (int)$p['id_penilaian'] ?></td>
                    <td>
                      <?php
                        $mulai   = $p['periode_mulai']   ?? null;
                        $selesai = $p['periode_selesai'] ?? null;
                        $nama    = $p['periode_nama']    ?? 'Periode';
                        echo htmlspecialchars($nama).' — '
                           . Yii::$app->formatter->asDate($mulai, 'php:d M Y')
                           . ' – '
                           . Yii::$app->formatter->asDate($selesai, 'php:d M Y');
                      ?>
                    </td>
                    <td class="text-right">
                      <?= Yii::$app->formatter->asDecimal((float)($p['nilai_akhir'] ?? 0), 2) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                  <tr><td colspan="3"><em>Belum ada penilaian.</em></td></tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
