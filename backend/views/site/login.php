<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход в панель управления';
?>
<div class="site-login">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-shield-lock"></i> <?= Html::encode($this->title) ?></h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Введите ваши учётные данные для входа:</p>

                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username', [
                            'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Введите логин']
                        ])->textInput(['autofocus' => true])->label('Логин') ?>

                        <?= $form->field($model, 'password', [
                            'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Введите пароль']
                        ])->passwordInput()->label('Пароль') ?>

                        <?= $form->field($model, 'rememberMe')->checkbox()->label('Запомнить меня') ?>

                        <div class="d-grid gap-2">
                            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-lg', 'name' => 'login-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
