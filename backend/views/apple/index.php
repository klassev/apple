<?php

/** @var yii\web\View $this */
/** @var common\models\Apple[] $apples */
/** @var array $groupedApples */

use common\models\Apple;
use yii\bootstrap5\Html;

$this->title = 'Яблоневый сад';
$this->params['breadcrumbs'][] = $this->title;

// Регистрируем CSS стили
$this->registerCss('
    /* Основной фон страницы */
    .apple-garden {
        min-height: 80vh;
        background: linear-gradient(180deg, #87CEEB 0%, #98D8AA 50%, #4A7C59 100%);
        border-radius: 20px;
        padding: 30px;
        position: relative;
        overflow: hidden;
    }
    
    /* Облака на фоне */
    .apple-garden::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 10%;
        width: 100px;
        height: 40px;
        background: rgba(255,255,255,0.8);
        border-radius: 50px;
        box-shadow: 
            30px 10px 0 10px rgba(255,255,255,0.8),
            60px -5px 0 5px rgba(255,255,255,0.8);
        animation: float-cloud 20s infinite linear;
    }
    
    .apple-garden::after {
        content: "";
        position: absolute;
        top: 60px;
        right: 20%;
        width: 80px;
        height: 30px;
        background: rgba(255,255,255,0.6);
        border-radius: 40px;
        box-shadow: 
            25px 8px 0 8px rgba(255,255,255,0.6),
            50px -3px 0 4px rgba(255,255,255,0.6);
        animation: float-cloud 25s infinite linear reverse;
    }
    
    @keyframes float-cloud {
        0% { transform: translateX(-100px); }
        100% { transform: translateX(calc(100vw + 100px)); }
    }
    
    /* Заголовок */
    .garden-title {
        color: #2d5016;
        text-shadow: 2px 2px 4px rgba(255,255,255,0.5);
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        font-weight: 700;
    }
    
    /* Дерево */
    .tree-section {
        background: linear-gradient(180deg, #228B22 0%, #006400 100%);
        border-radius: 50% 50% 5% 5%;
        padding: 40px 20px 20px;
        margin-bottom: 30px;
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .tree-section::after {
        content: "";
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 50px;
        background: linear-gradient(180deg, #8B4513 0%, #654321 100%);
        border-radius: 5px 5px 10px 10px;
    }
    
    .tree-title {
        color: white;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
    
    /* Земля */
    .ground-section {
        background: linear-gradient(180deg, #8B7355 0%, #6B5344 100%);
        border-radius: 15px;
        padding: 30px 20px;
        margin-bottom: 30px;
        box-shadow: inset 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .ground-title {
        color: #DEB887;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }
    
    /* Компост (гнилые) */
    .compost-section {
        background: linear-gradient(180deg, #4a4a4a 0%, #2d2d2d 100%);
        border-radius: 15px;
        padding: 30px 20px;
        margin-bottom: 30px;
        box-shadow: inset 0 5px 15px rgba(0,0,0,0.3);
    }
    
    .compost-title {
        color: #888;
    }
    
    /* Карточка яблока */
    .apple-card {
        background: rgba(255,255,255,0.95);
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .apple-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    }
    
    /* SVG яблоко */
    .apple-svg {
        width: 100px;
        height: 100px;
        filter: drop-shadow(3px 5px 5px rgba(0,0,0,0.3));
        transition: transform 0.3s ease;
    }
    
    .apple-card:hover .apple-svg {
        transform: scale(1.1);
    }
    
    .apple-on-tree .apple-svg {
        animation: swing 3s ease-in-out infinite;
    }
    
    @keyframes swing {
        0%, 100% { transform: rotate(-5deg); }
        50% { transform: rotate(5deg); }
    }
    
    .apple-rotten .apple-svg {
        filter: drop-shadow(3px 5px 5px rgba(0,0,0,0.3)) saturate(0.3) brightness(0.6);
    }
    
    /* Прогресс бар размера */
    .size-progress {
        height: 12px;
        border-radius: 10px;
        background: #e9ecef;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .size-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
        background: linear-gradient(90deg, var(--apple-color) 0%, var(--apple-color-light) 100%);
    }
    
    /* Статус бейджи */
    .status-badge-tree {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        box-shadow: 0 3px 10px rgba(39,174,96,0.4);
    }
    
    .status-badge-fallen {
        background: linear-gradient(135deg, #f39c12, #f1c40f);
        color: #333;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        box-shadow: 0 3px 10px rgba(243,156,18,0.4);
    }
    
    .status-badge-rotten {
        background: linear-gradient(135deg, #7f8c8d, #95a5a6);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        box-shadow: 0 3px 10px rgba(127,140,141,0.4);
    }
    
    /* Кнопки действий */
    .btn-fall {
        background: linear-gradient(135deg, #e67e22, #d35400);
        border: none;
        color: white;
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(230,126,34,0.4);
    }
    
    .btn-fall:hover {
        background: linear-gradient(135deg, #d35400, #e67e22);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(230,126,34,0.5);
        color: white;
    }
    
    .btn-eat {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        border: none;
        color: white;
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(39,174,96,0.4);
    }
    
    .btn-eat:hover {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(39,174,96,0.5);
        color: white;
    }
    
    .btn-delete-apple {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border: none;
        color: white;
        border-radius: 25px;
        padding: 6px 15px;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    
    .btn-delete-apple:hover {
        background: linear-gradient(135deg, #c0392b, #e74c3c);
        transform: translateY(-2px);
        color: white;
    }
    
    /* Кнопка генерации */
    .btn-generate {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        border: none;
        color: white;
        border-radius: 30px;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(155,89,182,0.4);
    }
    
    .btn-generate:hover {
        background: linear-gradient(135deg, #8e44ad, #9b59b6);
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(155,89,182,0.5);
        color: white;
    }
    
    /* Статистика */
    .stats-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-tree { background: linear-gradient(135deg, #27ae60, #2ecc71); }
    .stats-fallen { background: linear-gradient(135deg, #f39c12, #f1c40f); }
    .stats-rotten { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .stats-total { background: linear-gradient(135deg, #3498db, #2980b9); }
    
    /* Таймер */
    .timer-badge {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: white;
        padding: 3px 10px;
        border-radius: 10px;
        font-size: 0.75rem;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    /* Пустое состояние */
    .empty-state {
        background: rgba(255,255,255,0.9);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
    }
    
    .empty-state-icon {
        font-size: 80px;
        margin-bottom: 20px;
    }
    
    /* Быстрые кнопки процентов */
    .btn-percent {
        background: rgba(39,174,96,0.1);
        border: 2px solid #27ae60;
        color: #27ae60;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    
    .btn-percent:hover {
        background: #27ae60;
        color: white;
    }
    
    /* Инпут процентов */
    .percent-input {
        border-radius: 20px;
        border: 2px solid #27ae60;
        text-align: center;
        font-weight: 600;
    }
    
    .percent-input:focus {
        border-color: #2ecc71;
        box-shadow: 0 0 0 3px rgba(46,204,113,0.2);
    }
');
?>

<div class="apple-garden">
    <!-- Заголовок и кнопки управления -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h1 class="garden-title mb-0">🌳 <?= Html::encode($this->title) ?></h1>
        <div class="d-flex gap-2 flex-wrap">
            <?= Html::beginForm(['generate'], 'post', ['class' => 'd-inline-flex align-items-center gap-2']) ?>
                <?= Html::input('number', 'count', rand(3, 8), [
                    'class' => 'form-control',
                    'style' => 'width: 70px; border-radius: 20px; text-align: center;',
                    'min' => 1,
                    'max' => 50,
                ]) ?>
                <?= Html::submitButton('🌱 Вырастить яблоки', ['class' => 'btn btn-generate']) ?>
            <?= Html::endForm() ?>
            
            <?php if (!empty($apples)): ?>
                <?= Html::beginForm(['delete-all'], 'post', [
                    'class' => 'd-inline',
                    'data-confirm' => 'Удалить все яблоки из сада?'
                ]) ?>
                    <?= Html::submitButton('🗑️ Очистить сад', ['class' => 'btn btn-outline-dark', 'style' => 'border-radius: 20px;']) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($apples)): ?>
        <!-- Пустое состояние -->
        <div class="empty-state">
            <div class="empty-state-icon">🍎</div>
            <h3 class="text-muted mb-3">В саду пока нет яблок</h3>
            <p class="text-muted mb-0">Нажмите кнопку "Вырастить яблоки" чтобы посадить яблоневый сад!</p>
        </div>
    <?php else: ?>
        <!-- Статистика -->
        <div class="row mb-4 g-3">
            <div class="col-6 col-md-3">
                <div class="card stats-card stats-tree text-white h-100">
                    <div class="card-body text-center py-3">
                        <h2 class="mb-0 fw-bold"><?= count($groupedApples[Apple::STATUS_ON_TREE]) ?></h2>
                        <small>🌳 На дереве</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stats-card stats-fallen text-dark h-100">
                    <div class="card-body text-center py-3">
                        <h2 class="mb-0 fw-bold"><?= count($groupedApples[Apple::STATUS_FALLEN]) ?></h2>
                        <small>🍂 На земле</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stats-card stats-rotten text-white h-100">
                    <div class="card-body text-center py-3">
                        <h2 class="mb-0 fw-bold"><?= count($groupedApples[Apple::STATUS_ROTTEN]) ?></h2>
                        <small>🦠 Гнилые</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stats-card stats-total text-white h-100">
                    <div class="card-body text-center py-3">
                        <h2 class="mb-0 fw-bold"><?= count($apples) ?></h2>
                        <small>🍎 Всего</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Яблоки на дереве -->
        <?php if (!empty($groupedApples[Apple::STATUS_ON_TREE])): ?>
            <div class="tree-section mb-5">
                <h4 class="tree-title">🌳 Яблоки на дереве</h4>
                <div class="row g-3 justify-content-center">
                    <?php foreach ($groupedApples[Apple::STATUS_ON_TREE] as $apple): ?>
                        <?= $this->render('_apple_card', ['apple' => $apple]) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Упавшие яблоки -->
        <?php if (!empty($groupedApples[Apple::STATUS_FALLEN])): ?>
            <div class="ground-section">
                <h4 class="ground-title mb-4">🍂 Яблоки на земле</h4>
                <div class="row g-3">
                    <?php foreach ($groupedApples[Apple::STATUS_FALLEN] as $apple): ?>
                        <?= $this->render('_apple_card', ['apple' => $apple]) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Гнилые яблоки -->
        <?php if (!empty($groupedApples[Apple::STATUS_ROTTEN])): ?>
            <div class="compost-section">
                <h4 class="compost-title mb-4">🦠 Испорченные яблоки</h4>
                <div class="row g-3">
                    <?php foreach ($groupedApples[Apple::STATUS_ROTTEN] as $apple): ?>
                        <?= $this->render('_apple_card', ['apple' => $apple]) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
