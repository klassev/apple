<?php

/** @var yii\web\View $this */

use common\models\Apple;
use yii\bootstrap5\Html;

$this->title = 'Главная';

// Получаем статистику яблок
$totalApples = Apple::find()->count();
$applesOnTree = Apple::find()->where(['status' => Apple::STATUS_ON_TREE])->count();
$applesFallen = Apple::find()->where(['status' => Apple::STATUS_FALLEN])->count();
$applesRotten = Apple::find()->where(['status' => Apple::STATUS_ROTTEN])->count();

$this->registerCss('
    .dashboard-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(102,126,234,0.4);
    }
    
    .dashboard-hero h1 {
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .info-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    
    .info-card .card-body {
        padding: 25px;
    }
    
    .info-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 15px;
    }
    
    .card-system { background: linear-gradient(135deg, #3498db, #2980b9); }
    .card-apples { background: linear-gradient(135deg, #27ae60, #2ecc71); }
    .card-stats { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    
    .icon-system { background: rgba(52,152,219,0.2); }
    .icon-apples { background: rgba(39,174,96,0.2); }
    .icon-stats { background: rgba(231,76,60,0.2); }
    
    .apple-mini-stats {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .apple-mini-stat {
        background: rgba(255,255,255,0.1);
        padding: 10px 15px;
        border-radius: 10px;
        text-align: center;
        min-width: 80px;
    }
    
    .apple-mini-stat .number {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .apple-mini-stat .label {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    
    .quick-action-btn {
        border-radius: 25px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
    }
    
    .btn-garden {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        box-shadow: 0 5px 20px rgba(39,174,96,0.4);
    }
    
    .btn-garden:hover {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        box-shadow: 0 8px 25px rgba(39,174,96,0.5);
    }
');
?>

<div class="site-index">
    <!-- Hero секция -->
    <div class="dashboard-hero">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>👋 Добро пожаловать, <?= Html::encode(Yii::$app->user->identity->username) ?>!</h1>
                <p class="mb-4 opacity-75">Панель управления яблоневым садом</p>
                
                <?php if ($totalApples > 0): ?>
                    <div class="apple-mini-stats">
                        <div class="apple-mini-stat">
                            <div class="number"><?= $totalApples ?></div>
                            <div class="label">🍎 Всего</div>
                        </div>
                        <div class="apple-mini-stat">
                            <div class="number"><?= $applesOnTree ?></div>
                            <div class="label">🌳 На дереве</div>
                        </div>
                        <div class="apple-mini-stat">
                            <div class="number"><?= $applesFallen ?></div>
                            <div class="label">🍂 Упавших</div>
                        </div>
                        <div class="apple-mini-stat">
                            <div class="number"><?= $applesRotten ?></div>
                            <div class="label">🦠 Гнилых</div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="mb-0">В саду пока нет яблок. Посадите свой первый урожай!</p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <?= Html::a('🌳 Перейти в сад', ['/apple/index'], ['class' => 'btn quick-action-btn btn-garden btn-lg']) ?>
            </div>
        </div>
    </div>

    <!-- Карточки информации -->
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card info-card shadow-sm h-100">
                <div class="card-body">
                    <div class="info-card-icon icon-system">
                        ⚙️
                    </div>
                    <h5 class="card-title fw-bold">Информация о системе</h5>
                    <div class="text-muted">
                        <p class="mb-2"><strong>PHP:</strong> <?= phpversion() ?></p>
                        <p class="mb-2"><strong>Yii:</strong> <?= Yii::getVersion() ?></p>
                        <p class="mb-0"><strong>Время:</strong> <?= date('d.m.Y H:i:s') ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card info-card shadow-sm h-100">
                <div class="card-body">
                    <div class="info-card-icon icon-apples">
                        🍎
                    </div>
                    <h5 class="card-title fw-bold">Яблоневый сад</h5>
                    <p class="text-muted mb-3">Выращивайте, собирайте и ешьте виртуальные яблоки!</p>
                    <?= Html::a('Управление садом →', ['/apple/index'], ['class' => 'btn btn-outline-success btn-sm']) ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card info-card shadow-sm h-100">
                <div class="card-body">
                    <div class="info-card-icon icon-stats">
                        📊
                    </div>
                    <h5 class="card-title fw-bold">Статистика</h5>
                    <?php if ($totalApples > 0): ?>
                        <div class="text-muted">
                            <p class="mb-2">🌳 На дереве: <strong><?= $applesOnTree ?></strong></p>
                            <p class="mb-2">🍂 На земле: <strong><?= $applesFallen ?></strong></p>
                            <p class="mb-0">🦠 Испорчено: <strong><?= $applesRotten ?></strong></p>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">Пока нет данных для отображения</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
