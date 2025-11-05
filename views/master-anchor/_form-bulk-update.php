<?php
/** @var $model app\models\BatchAnchorInput */
/** @var $kriteria app\models\MasterKriteria */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$anchorsJson = json_encode($model->anchors, JSON_UNESCAPED_UNICODE);
$skalaNow    = (int)$model->skala;

$this->registerJs("window.ANCHORS_DATA = $anchorsJson; window.SKALA_NOW = $skalaNow;", View::POS_HEAD);

$this->registerJsFile('@web/js/bulk-update-anchor.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<?php $form = ActiveForm::begin(); ?>

<div class="card" style="padding:16px">
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'skala')->input('number', [
                'min' => 1,
                'id'  => 'input-skala',
            ])?>
        </div>
    </div>

    <?= Html::activeHiddenInput($model, 'id_kriteria'); ?>
    <?= Html::activeHiddenInput($model, 'id_departement'); ?>
    
    <div id="anchors-wrapper">
        <?php
        $renderRows = function($skala, $anchors) use ($form, $model) {
            ob_start(); ?>
            <div class="table-responsive">
                <table class="table table-bordered" style="background:#fff">
                    <thead>
                        <tr>
                            <th style="width:90px">Level</th>
                            <th>Deskripsi</th>
                            <th style="width:200px">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i <= $skala; $i++):
                            $desc = $anchors[$i]['deskripsi'] ?? '';
                            $val = $anchors[$i]['nilai_anchor'] ?? $i; ?>
                            <tr>
                                <td class="text-center align-middle"><strong><?= $i ?></strong></td>
                                <td>
                                    <textarea class="form-control"
                                              name="BatchAnchorInput[anchors][<?= $i ?>][deskripsi]"
                                              rows="2"
                                              placeholder="Uraian perilaku untuk level <?= $i ?>"><?= Html::encode($desc) ?></textarea>
                                </td>
                                <td>
                                    <input type="number"
                                           step="0.001"
                                           min="0"
                                           max="<?= (int)$model->skala ?>"
                                           class="form-control"
                                           name="BatchAnchorInput[anchors][<?= $i ?>][nilai_anchor]"
                                           value="<?= Html::encode($val) ?>">
                                    <small class="text-muted">Maksimal = skala saat ini.</small>
                                    <input type="hidden" 
                                           name="BatchAnchorInput[anchors][<?= $i ?>][level_anchor]" 
                                           value="<?= $i ?>">
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
        <?php return ob_get_clean(); };

        // render awal
        echo $renderRows((int)$model->skala, $model->anchors);
        ?>
    </div>

    <div class="alert alert-warning" id="warning-truncate" style="display:none">
        Level di atas skala baru akan <strong>dihapus</strong> saat disimpan.
    </div>

    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>