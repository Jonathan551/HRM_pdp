<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var yii\web\View $this */
/** @var app\models\MasterKriteria $kriteria */
/** @var app\models\MasterAnchor[] $anchors */

$this->title = $kriteria->nama_kriteria;
$this->params['breadcrumbs'][] = ['label' => 'Master Anchors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\kamran377\sweetalert2\SweetAlertAsset::register($this);

$this->registerJsFile(
    '@web/js/delete-confirm.js',
    [
        'depends'  => [\yii\web\JqueryAsset::class],
        'position' => View::POS_END,
    ]
);


$maxLevel = 0;
foreach ($anchors as $an) {
    if ($an->level_anchor > $maxLevel) {
        $maxLevel = $an->level_anchor;
    }
}


$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();

$flashError = Yii::$app->session->getFlash('error', null, true);
$flashSuccess = Yii::$app->session->getFlash('success', null, true);


function renderAlertBox($type, $message)
{
    $bg   = $type === 'success' ? '#4caf50' : '#f44336'; // hijau / merah
    $text = '#fff';
    $shadow = '0 2px 4px rgba(0,0,0,0.2)';

    return '<div style="
                background: '.$bg.';
                color: '.$text.';
                padding:15px 20px;
                margin-bottom:20px;
                border-radius:2px;
                box-shadow: '.$shadow.';
                font-size:13px;
            ">
                '.$message.'
            </div>';
}

?>

<div class="master-anchor-view">

    <h3 style="margin-top:0; margin-bottom:20px; color:#3C4858; font-weight:500;">
        <?= Html::encode($this->title) ?>
        <small style="font-size:14px; color:#888; font-weight:normal;">
            (Daftar Level / Skala Anchor)
        </small>
    </h3>

    <p class="mb-3">
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info btn-sm']) ?>

        <?= Html::a(
            'Tambah Level Anchor',
            ['create', 'id_kriteria' => $kriteria->id_kriteria],
            [
                'class' => 'btn btn-success btn-sm',
                'title' => 'Tambah skala baru untuk kriteria ini'
            ]
        ) ?>
    </p>

    <div class="card" style="border:1px solid #ddd; border-radius:4px;">
        <div class="card-header" style="padding:10px 15px; border-bottom:1px solid #eee;">
            <strong style="color:#3C4858;"><?= Html::encode($kriteria->nama_kriteria) ?></strong>
        </div>

        <div class="card-body" style="padding:0;">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width:70px; text-align:center; color:#9c27b0; font-weight:600;">Skala</th>
                        <th style="color:#9c27b0; font-weight:600;">Deskripsi</th>
                        <th style="width:130px; text-align:right; color:#9c27b0; font-weight:600;">Nilai Anchor</th>
                        <th style="width:140px; text-align:center; color:#9c27b0; font-weight:600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($anchors)): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; color:#999;">
                                Belum ada level anchor untuk kriteria ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($anchors as $a): ?>
                            <tr>
                                <td style="text-align:center;">
                                    <?= Html::encode($a->level_anchor) ?>
                                </td>
                                <td>
                                    <?= Html::encode($a->deskripsi) ?>
                                </td>
                                <td style="text-align:right;">
                                    <?= number_format($a->nilai_anchor, 3, ',', '.') ?>
                                </td>
                                <td style="text-align:center; white-space:nowrap;">

                                    <!-- EDIT selalu diijinkan -->
                                    <?= Html::a(
                                        '<i class="material-icons" style="font-size:16px;">edit</i>',
                                        ['update', 'id_anchor' => $a->id_anchor],
                                        [
                                            'class' => 'text-primary',
                                            'title' => 'Edit level ini',
                                            'style' => 'margin-right:8px; color:#3f51b5; text-decoration:none;',
                                        ]
                                    ) ?>

                                    <?php if ((int)$a->level_anchor === (int)$maxLevel): ?>
                                       <?= Html::a(
                                        '<i class="material-icons" style="font-size:16px;">delete</i>',
                                        ['delete', 'id_anchor' => $a->id_anchor],
                                        [
                                            'class' => 'text-danger',
                                            'title' => 'Hapus level ini',
                                            'data' => [
                                                'confirm' => 'Yakin hapus level ini?',
                                                'method' => 'post',
                                            ],
                                            'style' => 'color:#e91e63;'
                                        ]
                                        ) ?>
                                    <?php else: ?>
                                        <span style="color:#bbb; font-size:12px; font-style:italic;">
                                            tidak bisa hapus
                                        </span>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
