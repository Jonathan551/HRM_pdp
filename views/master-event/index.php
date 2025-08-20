<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\MasterEvent[] $models */

$this->title = 'Event Timeline';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/timeline.css', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<div class="master-event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="timeline">
        <?php foreach ($models as $event): ?>
            <div class="timeline-item">
                <div class="timeline-time">
                    <?= Yii::$app->formatter->asDate($event->tanggal, 'php:d-m-Y') ?>
                </div>

                <div class="timeline-icon <?= strtolower($event->severity) ?>">
                    <i class="fas fa-<?= $event->jenis_event == 'update' ? 'edit' : 'info-circle' ?>"></i>
                </div>

                <div class="timeline-content">
                    <h4>
                        <?= Html::encode($event->judul) ?>
                    </h4>

                    <p><?= Html::encode($event->deskripsi) ?></p>

                    <?php if ($event->gambar): ?>
                        <div class="timeline-image">
                            <?= Html::img('@web/uploads/'.$event->gambar, ['style'=>'max-width:150px; border-radius:6px;']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="meta mb-2">
                        <span class="badge bg-info">
                            <?= Html::encode($event->departement ? $event->departement->nama_departement : '-') ?>
                        </span>

                        <span class="badge bg-warning text-dark">
                            <?= Html::encode($event->jenis_event) ?>
                        </span>

                        <span class="badge severity-<?= strtolower($event->severity) ?>">
                            <?= Html::encode(ucfirst($event->severity)) ?>
                        </span>

                        <span class="badge bg-success">
                            <?= Html::encode($event->lokasi) ?>
                        </span>

              
                        <span class="badge status-<?= strtolower($event->status) ?>">
                            <?= Html::encode(ucfirst($event->status)) ?>
                        </span>

                        <span class="badge bg-dark">
                            Dibuat oleh: <?= Html::encode($event->createdBy ? $event->createdBy->nama : '-') ?>
                        </span>
                    </div>
                    <div class="timeline-actions">
                        <?= Html::a('<i class="fas fa-eye"></i> View', ['view','id_event'=>$event->id_event], ['class'=>'btn btn-sm btn-outline-primary']) ?>
                        <?= Html::a('<i class="fas fa-edit"></i> Update', ['update','id_event'=>$event->id_event], ['class'=>'btn btn-sm btn-outline-success']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
