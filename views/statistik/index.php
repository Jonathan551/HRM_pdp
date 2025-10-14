<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * 
 * @var array  $departemenList  
 * @var string $normLabel       
 * @var string $basis           
 * @var string $method          
 * @var int    $deptA
 * @var int    $deptB
 * @var array  $summary         
 * @var array  $top5
 * @var array  $bottom5
 * @var array  $labels          
 * @var array  $datasetA         
 * @var array  $datasetB        
 */

$this->title = 'Statistik — Banding 2 Departemen';
?>
<div class="card p-3">
  <h3 class="mb-3"><?= Html::encode($this->title) ?></h3>

  <?php
  $start = Yii::$app->request->get('start', '');
  $end   = Yii::$app->request->get('end', '');
  ?>

  <?php $form = ActiveForm::begin([
      'method'  => 'get',
      'action'  => ['statistik/index'],
      'options' => ['class' => 'row g-3 align-items-end mb-2']
  ]); ?>

    <div class="col-md-3">
      <label class="form-label">Departemen A</label>
      <?= Html::dropDownList('deptA', $deptA, $departemenList, [
          'class' => 'form-select',
          'prompt' => 'Pilih departemen...'
      ]) ?>
    </div>

    <div class="col-md-3">
      <label class="form-label">Departemen B</label>
      <?= Html::dropDownList('deptB', $deptB, $departemenList, [
          'class' => 'form-select',
          'prompt' => 'Pilih departemen...'
      ]) ?>
    </div>

    <div class="col-md-2">
      <label class="form-label">Metode</label>
      <?= Html::dropDownList('method', $method ?? 'minmax', [
          'minmax' => 'Min–Max (0–1)',
          'zscore' => 'Z-score',
      ], ['class' => 'form-select']) ?>
    </div>

    <div class="col-md-2">
      <label class="form-label">Basis</label>
      <?= Html::dropDownList('basis', $basis ?? 'perdept', [
          'perdept' => 'Per-departemen',
          'global'  => 'Global',
      ], ['class' => 'form-select']) ?>
    </div>

    <div class="col-md-2">
      <label class="form-label">Mulai</label>
      <input type="date" name="start" value="<?= Html::encode($start) ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <label class="form-label">Selesai</label>
      <input type="date" name="end" value="<?= Html::encode($end) ?>" class="form-control">
    </div>

    <div class="col-md-2 d-flex gap-2">
      <button class="btn btn-primary flex-fill" type="submit">Bandingkan</button>
      <?= Html::a('Reset', ['statistik/index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <div class="col-12">
      <button id="swap-dept" type="button" class="btn btn-sm btn-warning">
        Tukar Dept A ⇄ Dept B
      </button>
      <small class="text-muted ms-2">
        Normalisasi: <b><?= Html::encode($normLabel) ?></b>,
        Basis: <b><?= ($basis === 'perdept' ? 'Per-departemen' : 'Global') ?></b>.
      </small>
    </div>

  <?php ActiveForm::end(); ?>

  <?php if ($deptA && $deptB && isset($summary[$deptA], $summary[$deptB])): ?>
    <div class="row g-3 mt-2">
      <?php foreach ([$deptA, $deptB] as $id): $s = $summary[$id]; ?>
      <div class="col-md-6">
        <div class="border rounded p-3 h-100">
          <h5 class="mb-2"><?= Html::encode($s['dept'] ?? 'Departemen') ?></h5>
          <ul class="mb-0">
            <li>Total penilaian: <b><?= (int)($s['n_rows'] ?? 0) ?></b> (orang: <?= (int)($s['n_people'] ?? 0) ?>)</li>
            <li>Rata-rata normalisasi: <b><?= number_format((float)($s['avg_norm'] ?? 0), 3) ?></b></li>
            <li>Rata-rata nilai: <b><?= number_format((float)($s['avg_raw'] ?? 0), 2) ?></b> (σ <?= number_format((float)($s['std_raw'] ?? 0), 2) ?>)</li>
            <li>Min–Max nilai: <b><?= number_format((float)($s['min_raw'] ?? 0), 2) ?></b> – <b><?= number_format((float)($s['max_raw'] ?? 0), 2) ?></b></li>
          </ul>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <hr>

    <h5 class="mt-3">Perbandingan Visual</h5>
    <canvas id="cmpChart"></canvas>

    <div class="row mt-4">
      <div class="col-md-6">
        <h6>Top 5 (<?= Html::encode($summary[$deptA]['dept'] ?? 'Dept A') ?>)</h6>
        <ol class="mb-3">
          <?php foreach (($top5[$deptA] ?? []) as $r): ?>
            <li><?= Html::encode($r['nama']) ?> — <?= number_format((float)$r['norm_value'], 3) ?></li>
          <?php endforeach; ?>
        </ol>
        <h6>Bottom 5</h6>
        <ol class="mb-0">
          <?php foreach (($bottom5[$deptA] ?? []) as $r): ?>
            <li><?= Html::encode($r['nama']) ?> — <?= number_format((float)$r['norm_value'], 3) ?></li>
          <?php endforeach; ?>
        </ol>
      </div>

      <div class="col-md-6">
        <h6>Top 5 (<?= Html::encode($summary[$deptB]['dept'] ?? 'Dept B') ?>)</h6>
        <ol class="mb-3">
          <?php foreach (($top5[$deptB] ?? []) as $r): ?>
            <li><?= Html::encode($r['nama']) ?> — <?= number_format((float)$r['norm_value'], 3) ?></li>
          <?php endforeach; ?>
        </ol>
        <h6>Bottom 5</h6>
        <ol class="mb-0">
          <?php foreach (($bottom5[$deptB] ?? []) as $r): ?>
            <li><?= Html::encode($r['nama']) ?> — <?= number_format((float)$r['norm_value'], 3) ?></li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>

  <?php else: ?>
    <div class="alert alert-info mt-3">
      Silakan pilih <b>dua</b> departemen, lalu klik <b>Bandingkan</b>.
    </div>
  <?php endif; ?>
</div>

<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$labelsJson = json_encode($labels ?? [], JSON_UNESCAPED_UNICODE);
$dsA        = json_encode($datasetA ?? []);
$dsB        = json_encode($datasetB ?? []);
$nameA      = isset($summary[$deptA]['dept']) ? $summary[$deptA]['dept'] : 'Dept A';
$nameB      = isset($summary[$deptB]['dept']) ? $summary[$deptB]['dept'] : 'Dept B';

$js = <<<JS
$('#swap-dept').on('click', function(){
  var \$a = $('select[name="deptA"]');
  var \$b = $('select[name="deptB"]');
  var tmp = \$a.val();
  \$a.val(\$b.val()).trigger('change');
  \$b.val(tmp).trigger('change');
});


if (document.getElementById('cmpChart') && $labelsJson.length > 0) {
  var ctx = document.getElementById('cmpChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: $labelsJson,
      datasets: [
        { label: '$nameA', data: $dsA },
        { label: '$nameB', data: $dsB }
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top' } },
      scales: { y: { beginAtZero: true } }
    }
  });
}
JS;

$this->registerJs($js);
