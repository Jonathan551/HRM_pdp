<?php
use yii\helpers\Html;

/** @var $model app\models\MasterProfile */
$this->title = 'Company Profile';

$this->registerCssFile('@web/css/profile.css', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="profile-container">
  <div class="profile-card">
    <div class="profile-header">
      <h4><?= Html::encode($this->title) ?></h4>
      <p>Informasi umum perusahaan</p>
      <img 
        src="<?= Yii::getAlias('@web/uploads/') . ($model->logo ?: 'default-logo.png') ?>" 
        alt="Logo"
        class="profile-logo"
      >
    </div>
    <div class="profile-body">
      <div class="profile-row">
        <div class="profile-col">
          <div class="profile-label">Nama</div>
          <div class="profile-value"><?= Html::encode($model->nama) ?></div>
        </div>
        <div class="profile-col">
          <div class="profile-label">Email</div>
          <div class="profile-value"><?= Html::encode($model->email) ?></div>
        </div>
        <div class="profile-col">
          <div class="profile-label">No. Telepon</div>
          <div class="profile-value"><?= Html::encode($model->notelfon) ?></div>
        </div>
        <div class="profile-col">
          <div class="profile-label">Alamat</div>
          <div class="profile-value"><?= Html::encode($model->alamat) ?></div>
        </div>
      </div>

      <div class="text-center mt-4">
        <?= Html::a(
          '<i class="material-icons">edit</i> Edit Profil',
          ['update', 'id' => $model->id_profile],
          ['class' => 'btn btn-info btn-round']
        ) ?>
      </div>
    </div>
  </div>
</div>
