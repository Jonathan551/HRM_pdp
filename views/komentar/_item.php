<?php

use yii\helpers\Html;

/** @var \app\models\Komentar $model */
$fmt = Yii::$app->formatter;
$web = Yii::getAlias('@web');
$webroot = Yii::getAlias('@webroot');

$name = 'User #' . $model->id_users;
if ($model->users) {
    foreach (['nama', 'name', 'fullname', 'full_name', 'display_name', 'username'] as $attr) {
        if (!empty($model->users->$attr)) { $name = $model->users->$attr; break; }
    }
}

$avatar = $web . '/images/no-avatar.jpeg';
if ($model->users ?? false) {
    $foto = trim((string)$model->users->foto);
    if ($foto !== '') {
        if (preg_match('~^https?://~i', $foto)) {
            $avatar = $foto;
        } else {
            $enc = rawurlencode(str_replace('\\','/',$foto));
            if (is_file($webroot . '/uploads/' . $enc)) {
                $avatar = $web . '/uploads/' . $enc;
            } else {
                if (is_file($webroot . '/uploads/users/' . $enc)) {
                    $avatar = $web . '/uploads/users/' . $enc;
                }
            }
        }
    }
}
?>
<div class="comment-item">
  <img class="comment-avatar" src="<?= Html::encode($avatar) ?>" alt="avatar">
  <div class="comment-main">
    <div class="comment-header">
      <span class="comment-author"><?= Html::encode($name) ?></span>
      <span class="comment-dot">•</span>
      <span class="comment-date"><?= $fmt->asDatetime($model->created_at, 'php:d M Y H:i') ?></span>
    </div>
    <div class="comment-content">
      <?= nl2br(Html::encode($model->deskripsi)) ?>
    </div>
  </div>
</div>