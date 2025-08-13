<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LoginForm */

$this->title = 'HRM';
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5 ml-auto mr-auto"> 
        <div class="card card-login" style="padding: 20px;">
            <div class="card-header card-header-info text-center">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3> 
            </div>

            <div class="card-body" style="padding: 25px;">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'form'],
                ]); ?>

                <?= $form->field($model, 'username', [
                    'options' => ['class' => 'bmd-form-group'],
                    'inputOptions' => [
                        'autofocus' => true,
                        'class' => 'form-control form-control-lg', 
                        'placeholder' => 'Username'
                    ],
                ])->label(false) ?>

                <?= $form->field($model, 'password', [
                    'options' => ['class' => 'bmd-form-group'],
                    'inputOptions' => [
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Password'
                    ],
                ])->passwordInput()->label(false) ?>
            </div>

            <div class="card-footer text-center">
                <?= Html::submitButton('LOGIN', [
                    'class' => 'btn btn-info btn-wd btn-lg', 
                    'name' => 'login-button',
                    'style' => 'display:block;margin:0 auto;'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
