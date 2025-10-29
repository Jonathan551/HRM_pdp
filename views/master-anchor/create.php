<?php
/** @var yii\web\View $this */
/** @var app\models\BatchAnchorInput$model */
/** @var array $departemenDropdown */

use yii\web\View;
use yii\helpers\Url;

$this->title = 'Create Master Anchor';
$this->params['breadcrumbs'][] = ['label' => 'Master Anchor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$this->registerJsFile(
    '@web/js/master-anchor-form.js',
    [
        'depends' => [\yii\web\JqueryAsset::class],
    ]
);

$defaultSkala = isset($model->skala) && $model->skala ? (int)$model->skala : 1;


$initJs = <<<JS
    window.MASTER_ANCHOR_INIT = {
        defaultSkala: {$defaultSkala}
    };
JS;

$this->registerJs($initJs, View::POS_HEAD);
?>

<div class="master-anchor-create">

    <h3 style="margin-top:0; color:#3C4858; font-weight:500;">
        <?= $this->title ?>
    </h3>

    <?= $this->render('_form', [
        'model' => $model,
        'departementDropdown' => $departementDropdown,
    ]) ?>

</div>
