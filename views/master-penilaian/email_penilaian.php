<?php
/** @var \app\models\MasterPenilaian $model */
$user = $model->user;
?>
<p>Halo <?= htmlspecialchars($user->nama ?? 'Karyawan', ENT_QUOTES) ?>,</p>
<p>Terlampir hasil penilaian periode
<strong><?= htmlspecialchars($model->periode_awal, ENT_QUOTES) ?> - <?= htmlspecialchars($model->periode_akhir, ENT_QUOTES) ?></strong>
dengan skor akhir <strong><?= htmlspecialchars((string)$model->nilai_akhir, ENT_QUOTES) ?></strong>.</p>
<p>Terima kasih</p>