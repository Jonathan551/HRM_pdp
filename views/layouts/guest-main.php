<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

// Pastikan asset Material Dashboard ter-register
\deyraka\materialdashboard\web\MaterialDashboardAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="login-page sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper wrapper-full-page">
    <div class="page-header login-page" style="background-color: white; min-height: 100vh; display: flex; align-items: center;">
        <div class="container">
            <?= $content ?>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
