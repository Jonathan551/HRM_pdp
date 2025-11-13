<?php
use yii\helpers\Url;
use yii\helpers\Html;

$pollUrl    = Url::to(['/notification/poll']);
$listUrl    = Url::to(['/notification/index']);
$readAllUrl = Url::to(['/notification/read-all']);
?>
<nav class="navbar navbar-transparent navbar-expand-lg navbar-absolute fixed-top" role="navigation-demo">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="#"><?= Html::encode($this->title) ?></a>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item dropdown">
          <a class="nav-link"
             href="#"
             id="notifDropdown"
             data-toggle="dropdown"
             aria-haspopup="true"
             aria-expanded="false"
             data-poll-url="<?= Html::encode($pollUrl) ?>"
             data-list-url="<?= Html::encode($listUrl) ?>"
             data-readall-url="<?= Html::encode($readAllUrl) ?>">
            <i class="material-icons">notifications</i>
            <span id="notifBadge" class="notification" style="display:none">0</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifDropdown" style="min-width:320px">
            <div class="px-3 py-2 d-flex justify-content-between align-items-center">
              <strong>Notifikasi</strong>
              <a class="small" id="notifReadAllLink" href="<?= Html::encode($readAllUrl) ?>">Tandai semua dibaca</a>
            </div>
            <div id="notifItems">
              <a class="dropdown-item disabled" href="javascript:void(0)">Memuat...</a>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-center" id="notifSeeAllLink" href="<?= Html::encode($listUrl) ?>">Lihat semua</a>
          </div>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">person</i>
          </a>
         <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('Log in', ['/site/login'], ['class'=>'dropdown-item']) ?>
            <?php else: ?>
                <?= Html::a('Profile', ['/master-profile/index'], ['class'=>'dropdown-item']) ?>
                <div class="dropdown-divider"></div>
                <?= Html::a(
                      'Log out (' . Yii::$app->user->identity->username . ')',
                      ['/site/logout'],
                      ['class'=>'dropdown-item', 'data-method'=>'post']
                    ) ?>
            <?php endif; ?>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<?php

$this->registerCssFile('https://fonts.googleapis.com/icon?family=Material+Icons');

$this->registerCss("
  .navbar .nav-link .material-icons { font-size:22px; color:#344767; vertical-align:middle; }
  .notification { background:#dc3545; color:#fff; border-radius:10px; padding:0 6px; font-size:12px; margin-left:4px; }
");
?>

<?php
$this->registerJsFile('@web/js/notif.js', [
  'depends' => [
    \yii\web\YiiAsset::class,
    \yii\bootstrap5\BootstrapPluginAsset::class, // Kamu pakai BS4 (Material Dashboard)
  ],
]);
?>