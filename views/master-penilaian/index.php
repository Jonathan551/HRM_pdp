<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\MasterPenilaiansearch $searchModel */
/** @var \app\models\MasterPeriode[] $periodes */

$this->title = 'Master Penilaian';
$this->registerCss(<<<CSS
.hdr-purple { color:#6f42c1 !important; }   
CSS);

?>
<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
  'dataProvider' => $dataProvider,
  'filterModel'  => $searchModel,
  'columns' => [
    ['class'=>'yii\grid\SerialColumn'],
    [
      'attribute' => 'id_users',
      'label'     => 'Karyawan',
      'value'     => fn($m) => $m->user->nama ?? $m->id_users,
      'filter'    => false,
    ],
    [
      'attribute' => 'nilai_akhir',
      'label'     => 'Nilai Akhir',
      'value'     => fn($m) => $m->nilai_akhir ?? 'Tidak ada',
      'filter'    => false,
    ],
    [
      'attribute' => 'id_periode',
      'label'     => 'Periode Penilaian',
      'value'     => function ($m) {
        if (!$m->periode) return '-';
        return sprintf('%s (%s s/d %s)',
          $m->periode->nama,
          $m->periode->tanggal_mulai,
          $m->periode->tanggal_selesai
        );
      },
      'filter' => Html::activeDropDownList(
        $searchModel,
        'id_periode',
        ArrayHelper::map(
          $periodes,
          'id_periode',
          fn($p) => $p->nama.' ('.$p->tanggal_mulai.' s/d '.$p->tanggal_selesai.')'
        ),
        ['class'=>'form-control','prompt'=>'— Pilih Periode —']
      ),
    ],
    [
      'attribute' => 'presentase_absensi',
      'label'     => 'Presentase Absensi',
      'value'     => fn($m) => $m->presentase_absensi ?? 'Tidak ada',
      'filter'    => false,
    ],
    [
      'attribute' => 'catatan',
      'format'    => 'ntext',
      'filter'    => false,
    ],
    [
      'attribute' => 'rekomendasi',
      'format'    => 'ntext',
      'filter'    => false,
    ],
    [
      'class' => 'yii\grid\ActionColumn',
      'header'=> 'Aksi',
      'headerOptions' => ['class'=>'hdr-purple'],
      'template' => '{isi} {notes} {view} {update} {delete}',
      'buttons' => [
        'isi' => function ($url, $model) {
          $isLocked = ($model->periode && (strtotime($model->periode->tanggal_selesai) < strtotime('today') || strtolower((string)$model->periode->status)==='locked'));
          $isBaru   = !$model->getDetailPenilaian()->exists() && $model->nilai_akhir === null;
          if ($isLocked || !$isBaru) return '';
          return Html::a('Isi Penilaian', ['update','id_penilaian'=>$model->id_penilaian]);
        },
        'notes' => function ($url, $model) {
          $isLocked = ($model->periode && (strtotime($model->periode->tanggal_selesai) < strtotime('today') || strtolower((string)$model->periode->status)==='locked'));
          $hasDetail  = $model->getDetailPenilaian()->exists() || $model->nilai_akhir !== null;
          $notesEmpty = empty($model->catatan) && empty($model->rekomendasi);
          if ($isLocked || !$hasDetail || !$notesEmpty) return '';
          return Html::a('Tulis Catatan & Rekomendasi', ['notes','id_penilaian'=>$model->id_penilaian]);
        },
      ],
      'visibleButtons' => [
        'view'   => fn($m) => true,
        'update' => fn($m) => !($m->periode && (strtotime($m->periode->tanggal_selesai) < strtotime('today') || strtolower((string)$m->periode->status)==='locked')) && (!empty($m->catatan) || !empty($m->rekomendasi)),
        'delete' => fn($m) => !($m->periode && (strtotime($m->periode->tanggal_selesai) < strtotime('today') || strtolower((string)$m->periode->status)==='locked')) && (!empty($m->catatan) || !empty($m->rekomendasi)),
      ],
      'contentOptions' => ['class'=>'action-purple'],
      'urlCreator' => fn($action,$m)=>['master-penilaian/'.$action,'id_penilaian'=>$m->id_penilaian],
    ],
  ],
]); ?>
