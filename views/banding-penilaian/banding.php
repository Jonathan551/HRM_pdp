<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
/** @var app\models\BandingPenilaian $model */

$this->title = "Review Banding #" . $model->id_banding;
$this->params['breadcrumbs'][] = ['label' => 'Banding Penilaian', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="banding-penilaian-review">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id_penilaian',
                'label' => 'Nomor Laporan Penilaian',
            ],
            [
                'attribute' => 'id_users',
                'label' => 'User',
                'value' => function ($model) {
                    return $model->user ? $model->user->nama : '-';
                },
            ],
            'status',
            [
                'attribute' => 'tanggal_banding',
                'format' => ['date', 'php:d-m-Y'],
            ],
            'alasan:ntext',
        ],
    ]) ?>

    <?php $form = ActiveForm::begin([
        'action' => ['banding-penilaian/review', 'id_banding' => $model->id_banding],
        'method' => 'post',
    ]); ?>

        <?= $form->field($model, 'review')->textarea(['rows' => 4]) ?>

        <div class="form-group">
            <?= Html::submitButton('Ditolak', [
                'class' => 'btn btn-danger',
                'name' => 'submitBtn',
                'value' => 'tolak'
            ]) ?>

            <?= Html::submitButton('Diterima', [
                'class' => 'btn btn-success',
                'name' => 'submitBtn',
                'value' => 'terima'
            ]) ?>

            <?= Html::button('Detail', [
                'value' => \yii\helpers\Url::to(['master-penilaian/view-modal', 'id_penilaian' => $model->id_penilaian]),
                'class' => 'btn btn-warning',
                'id' => 'btn-detail'
            ]) ?>

            <?= Html::a('Kembali', ['banding-penilaian/index'], ['class' => 'btn btn-info']) ?>
        </div>
    
    <?php ActiveForm::end(); ?>
    
</div>

<?php
    Modal::begin([
        'title' => '<h4>Detail Penilaian</h4>',
        'id' => 'modal-detail',
        'size' => 'modal-lg',
    ]);

    echo "<div id='modalContent'></div>";

    Modal::end();
    ?>

    <?php
    $this->registerJs("
        $('#btn-detail').on('click', function() {
            $('#modal-detail').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'));
        });
    ");
?>
