<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Komentar $model */
$fmt = Yii::$app->formatter;
$web = Yii::getAlias('@web');
$webroot = Yii::getAlias('@webroot');

$me = Yii::$app->user->identity->id_users ?? null;

/* --- ambil nama --- */
$name = 'User #' . $model->id_users;
if ($model->users ?? null) {
    foreach (['nama','name','fullname','full_name','display_name','username'] as $attr) {
        if (!empty($model->users->$attr)) { $name = $model->users->$attr; break; }
    }
}

/* --- avatar --- */
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
            } elseif (is_file($webroot . '/uploads/users/' . $enc)) {
                $avatar = $web . '/uploads/users/' . $enc;
            }
        }
    }
}
?>

<div class="comment-item" id="comment-<?= (int)$model->id_komentar ?>">
  <img class="comment-avatar" src="<?= Html::encode($avatar) ?>" alt="avatar">
  <div class="comment-main">
    <div class="comment-header d-flex align-items-center justify-content-between">
      <div>
        <span class="comment-author"><?= Html::encode($name) ?></span>
        <span class="comment-dot">•</span>
        <span class="comment-date"><?= $fmt->asDatetime($model->created_at, 'php:d M Y H:i') ?></span>
      </div>

      <?php if ($me && (int)$me === (int)$model->id_users): ?>
        <?= Html::button('Edit', [
            'class'    => 'btn btn-sm btn-primary btn-komen-edit',
            'data-id'  => $model->id_komentar,
            'data-url' => Url::to(['komentar/update', 'id' => $model->id_komentar]),
        ]) ?>
      <?php endif; ?>
    </div>

    <div class="comment-content mt-2">
      <?= nl2br(Html::encode($model->deskripsi)) ?>
    </div>

    <div class="comment-edit-box d-none mt-3" id="edit-box-<?= (int)$model->id_komentar ?>"></div>
  </div>
</div>
