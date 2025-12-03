<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';

$this->registerCss('
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .login-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 30px;
        text-align: center;
        color: white;
    }
    
    .login-icon {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 36px;
    }
    
    .login-header h2 {
        margin: 0;
        font-weight: 700;
    }
    
    .login-header p {
        margin: 10px 0 0;
        opacity: 0.85;
    }
    
    .login-body {
        padding: 40px;
    }
    
    .form-control {
        border-radius: 12px;
        padding: 14px 18px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
    }
    
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 14px 30px;
        font-weight: 600;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .form-check-label {
        color: #666;
    }
    
    /* Плавающий декор */
    .floating-apple {
        position: fixed;
        font-size: 40px;
        opacity: 0.15;
        animation: float 6s ease-in-out infinite;
        pointer-events: none;
    }
    
    .floating-apple:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
    .floating-apple:nth-child(2) { top: 20%; right: 15%; animation-delay: 1s; }
    .floating-apple:nth-child(3) { bottom: 30%; left: 5%; animation-delay: 2s; }
    .floating-apple:nth-child(4) { bottom: 15%; right: 10%; animation-delay: 3s; }
    .floating-apple:nth-child(5) { top: 50%; left: 3%; animation-delay: 4s; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(10deg); }
    }
');
?>

<!-- Плавающие яблоки на фоне -->
<div class="floating-apple">🍎</div>
<div class="floating-apple">🍏</div>
<div class="floating-apple">🍎</div>
<div class="floating-apple">🍏</div>
<div class="floating-apple">🍎</div>

<div class="site-login">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-10">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-icon">🍎</div>
                    <h2>Яблоневый сад</h2>
                    <p>Панель управления</p>
                </div>
                
                <div class="login-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}",
                            'labelOptions' => ['class' => 'form-label'],
                            'inputOptions' => ['class' => 'form-control'],
                            'errorOptions' => ['class' => 'invalid-feedback'],
                        ],
                    ]); ?>

                        <?= $form->field($model, 'username')
                            ->textInput([
                                'autofocus' => true,
                                'placeholder' => 'Введите логин'
                            ])
                            ->label('Логин') ?>

                        <?= $form->field($model, 'password')
                            ->passwordInput([
                                'placeholder' => 'Введите пароль'
                            ])
                            ->label('Пароль') ?>

                        <?= $form->field($model, 'rememberMe')
                            ->checkbox([
                                'template' => "<div class=\"form-check\">{input} {label}</div>\n{error}",
                            ])
                            ->label('Запомнить меня') ?>

                        <div class="d-grid mt-4">
                            <?= Html::submitButton('Войти в систему', [
                                'class' => 'btn btn-login',
                                'name' => 'login-button'
                            ]) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            
            <p class="text-center mt-4 text-white-50">
                <small>© <?= date('Y') ?> Яблоневый сад. Все права защищены.</small>
            </p>
        </div>
    </div>
</div>
