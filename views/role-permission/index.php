<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var $jabatans app\models\MasterJabatan[] */
/** @var $permissions app\models\Permissions[] */
/** @var $assigned int[] */
/** @var $id_jabatan int|null */
?>
<h3>Manajemen Permission</h3>

<div class="card p-3">
    <?= Html::dropDownList(
        'id_jabatan',
        $id_jabatan,
        ArrayHelper::map($jabatans, 'id_jabatan', 'nama_jabatan'),
        [
            'class' => 'form-control',
            'prompt' => 'Pilih Jabatan',
            'onchange' => 'location.href="' . Url::to(['role-permission/index']) . '&id_jabatan=" + this.value;'
        ]
    ); ?>

    <?php if ($id_jabatan): ?>
        <hr>

        <?php $form = ActiveForm::begin([
            'action' => ['role-permission/index', 'id_jabatan' => $id_jabatan],
            'method' => 'post'
        ]); ?>

        <div class="list-group" style="max-height:480px; overflow:auto;">
            <?php foreach ($permissions as $perm): ?>
                <label class="list-group-item d-flex align-items-center gap-2">
                    <input type="checkbox"
                           name="permissions[]"
                           value="<?= $perm->id_permission ?>"
                           <?= in_array($perm->id_permission, $assigned) ? 'checked' : '' ?>>
                    <strong><?= Html::encode($perm->nama_permission) ?></strong>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="mt-3">
            <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-info']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>