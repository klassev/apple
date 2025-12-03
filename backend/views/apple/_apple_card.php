<?php

/** @var yii\web\View $this */
/** @var common\models\Apple $apple */

use common\models\Apple;
use yii\bootstrap5\Html;

// Определяем CSS переменные для цвета
$colorMap = [
    'red' => ['main' => '#e74c3c', 'light' => '#ff6b6b', 'dark' => '#c0392b'],
    'green' => ['main' => '#27ae60', 'light' => '#2ecc71', 'dark' => '#1e8449'],
    'yellow' => ['main' => '#f1c40f', 'light' => '#f9e79f', 'dark' => '#d4ac0d'],
    'orange' => ['main' => '#e67e22', 'light' => '#f39c12', 'dark' => '#ca6f1e'],
    'pink' => ['main' => '#fd79a8', 'light' => '#ffadd2', 'dark' => '#e056a0'],
];

$colors = $colorMap[$apple->color] ?? $colorMap['red'];
$statusClass = '';
$statusBadgeClass = '';

switch ($apple->status) {
    case Apple::STATUS_ON_TREE:
        $statusClass = 'apple-on-tree';
        $statusBadgeClass = 'status-badge-tree';
        break;
    case Apple::STATUS_FALLEN:
        $statusClass = '';
        $statusBadgeClass = 'status-badge-fallen';
        break;
    case Apple::STATUS_ROTTEN:
        $statusClass = 'apple-rotten';
        $statusBadgeClass = 'status-badge-rotten';
        break;
}

// SVG яблоко
$appleSvg = <<<SVG
<svg class="apple-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <!-- Тень -->
    <ellipse cx="50" cy="95" rx="25" ry="5" fill="rgba(0,0,0,0.2)"/>
    
    <!-- Листик -->
    <path d="M50 15 Q60 5 70 15 Q60 20 50 15" fill="#27ae60"/>
    <path d="M50 15 Q55 10 60 15" stroke="#1e8449" stroke-width="1" fill="none"/>
    
    <!-- Веточка -->
    <path d="M50 15 L50 25" stroke="#8B4513" stroke-width="3" stroke-linecap="round"/>
    
    <!-- Основа яблока -->
    <ellipse cx="50" cy="55" rx="35" ry="38" fill="{$colors['main']}"/>
    
    <!-- Блик -->
    <ellipse cx="35" cy="45" rx="12" ry="15" fill="{$colors['light']}" opacity="0.5"/>
    <ellipse cx="32" cy="42" rx="6" ry="8" fill="white" opacity="0.4"/>
    
    <!-- Тень внизу -->
    <ellipse cx="55" cy="75" rx="20" ry="10" fill="{$colors['dark']}" opacity="0.3"/>
    
    <!-- Впадинка сверху -->
    <path d="M40 28 Q50 35 60 28" stroke="{$colors['dark']}" stroke-width="2" fill="none" opacity="0.5"/>
    
    <!-- Впадинка снизу -->
    <path d="M42 85 Q50 80 58 85" stroke="{$colors['dark']}" stroke-width="2" fill="none" opacity="0.3"/>
</svg>
SVG;

// SVG для надкусанного яблока
$bittenSvg = <<<SVG
<svg class="apple-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <!-- Тень -->
    <ellipse cx="50" cy="95" rx="25" ry="5" fill="rgba(0,0,0,0.2)"/>
    
    <!-- Листик -->
    <path d="M50 15 Q60 5 70 15 Q60 20 50 15" fill="#27ae60"/>
    
    <!-- Веточка -->
    <path d="M50 15 L50 25" stroke="#8B4513" stroke-width="3" stroke-linecap="round"/>
    
    <!-- Основа яблока с укусом -->
    <path d="M85 55 
             A35 38 0 1 1 50 93 
             A35 38 0 0 1 15 55
             A35 38 0 0 1 50 17
             A35 38 0 0 1 85 55
             Q80 50 75 55
             Q70 45 78 40
             Q85 50 85 55
             Z" fill="{$colors['main']}"/>
    
    <!-- Мякоть укуса -->
    <path d="M78 40 Q85 50 85 55 Q80 50 75 55 Q70 45 78 40" fill="#fffacd"/>
    <circle cx="80" cy="48" r="1.5" fill="#8B4513" opacity="0.5"/>
    <circle cx="77" cy="52" r="1" fill="#8B4513" opacity="0.5"/>
    
    <!-- Блик -->
    <ellipse cx="35" cy="45" rx="12" ry="15" fill="{$colors['light']}" opacity="0.5"/>
    <ellipse cx="32" cy="42" rx="6" ry="8" fill="white" opacity="0.4"/>
</svg>
SVG;

$displaySvg = $apple->eaten_percent > 0 ? $bittenSvg : $appleSvg;
?>

<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <div class="apple-card h-100 <?= $statusClass ?>" style="--apple-color: <?= $colors['main'] ?>; --apple-color-light: <?= $colors['light'] ?>;">
        <!-- SVG яблоко -->
        <div class="mb-2">
            <?= $displaySvg ?>
        </div>
        
        <!-- Название и статус -->
        <h6 class="mb-2 fw-bold" style="color: <?= $colors['main'] ?>">
            <?= Html::encode($apple->colorLabel) ?> #<?= $apple->id ?>
        </h6>
        <span class="<?= $statusBadgeClass ?>"><?= Html::encode($apple->statusLabel) ?></span>
        
        <!-- Индикатор размера -->
        <div class="mt-3 mb-2">
            <div class="d-flex justify-content-between small mb-1">
                <span class="text-muted">Осталось:</span>
                <span class="fw-bold" style="color: <?= $colors['main'] ?>"><?= number_format($apple->size * 100, 0) ?>%</span>
            </div>
            <div class="size-progress">
                <div class="size-progress-bar" style="width: <?= $apple->size * 100 ?>%"></div>
            </div>
        </div>
        
        <!-- Информация -->
        <div class="small text-muted mb-3">
            <div>📅 <?= $apple->createdAtFormatted ?></div>
            <?php if ($apple->fallenAtFormatted): ?>
                <div>⬇️ <?= $apple->fallenAtFormatted ?></div>
            <?php endif; ?>
            <?php if ($apple->isFallen && $apple->timeUntilRottenFormatted): ?>
                <div class="timer-badge mt-1">⏰ <?= $apple->timeUntilRottenFormatted ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Кнопки действий -->
        <div class="d-grid gap-2">
            <?php if ($apple->isOnTree): ?>
                <!-- Яблоко на дереве -->
                <?= Html::beginForm(['fall', 'id' => $apple->id], 'post') ?>
                    <?= Html::submitButton('⬇️ Уронить на землю', ['class' => 'btn btn-fall w-100']) ?>
                <?= Html::endForm() ?>
                
                <small class="text-muted text-center">🚫 Нельзя съесть на дереве</small>
                
            <?php elseif ($apple->isFallen): ?>
                <!-- Яблоко на земле -->
                <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-flex gap-2 mb-2']) ?>
                    <?= Html::input('number', 'percent', 25, [
                        'class' => 'form-control percent-input',
                        'min' => 1,
                        'max' => 100,
                        'style' => 'width: 65px;',
                    ]) ?>
                    <?= Html::submitButton('🍴 Откусить', ['class' => 'btn btn-eat flex-grow-1']) ?>
                <?= Html::endForm() ?>
                
                <div class="d-flex gap-1 justify-content-center">
                    <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                        <?= Html::hiddenInput('percent', 25) ?>
                        <?= Html::submitButton('25%', ['class' => 'btn btn-percent btn-sm']) ?>
                    <?= Html::endForm() ?>
                    <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                        <?= Html::hiddenInput('percent', 50) ?>
                        <?= Html::submitButton('50%', ['class' => 'btn btn-percent btn-sm']) ?>
                    <?= Html::endForm() ?>
                    <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                        <?= Html::hiddenInput('percent', 100) ?>
                        <?= Html::submitButton('100%', ['class' => 'btn btn-percent btn-sm']) ?>
                    <?= Html::endForm() ?>
                </div>
                
            <?php else: ?>
                <!-- Гнилое яблоко -->
                <div class="text-center text-muted mb-2">
                    <span class="fs-4">🤢</span><br>
                    <small>Несъедобно</small>
                </div>
            <?php endif; ?>
            
            <!-- Кнопка удаления -->
            <?= Html::beginForm(['delete', 'id' => $apple->id], 'post', [
                'class' => 'mt-2',
                'data-confirm' => 'Удалить это яблоко?'
            ]) ?>
                <?= Html::submitButton('🗑️ Удалить', ['class' => 'btn btn-delete-apple w-100']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>
