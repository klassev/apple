<?php

/** @var yii\web\View $this */
/** @var common\models\Apple $apple */

use common\models\Apple;
use yii\bootstrap5\Html;

$statusClass = '';
$statusBadgeClass = '';

switch ($apple->status) {
    case Apple::STATUS_ON_TREE:
        $statusClass = 'apple-on-tree';
        $statusBadgeClass = 'bg-success';
        break;
    case Apple::STATUS_FALLEN:
        $statusClass = '';
        $statusBadgeClass = 'bg-warning text-dark';
        break;
    case Apple::STATUS_ROTTEN:
        $statusClass = 'apple-rotten';
        $statusBadgeClass = 'bg-danger';
        break;
}
?>

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card apple-card h-100 shadow-sm">
        <div class="card-body text-center">
            <!-- Иконка яблока -->
            <div class="apple-icon <?= $statusClass ?>" style="color: <?= $apple->colorHex ?>">
                🍎
            </div>
            
            <!-- Цвет и статус -->
            <h5 class="card-title mt-2 mb-1"><?= Html::encode($apple->colorLabel) ?></h5>
            <span class="badge status-badge <?= $statusBadgeClass ?>"><?= Html::encode($apple->statusLabel) ?></span>
            
            <!-- Информация -->
            <div class="mt-3 text-start small text-muted">
                <div><strong>ID:</strong> #<?= $apple->id ?></div>
                <div><strong>Появилось:</strong> <?= $apple->createdAtFormatted ?></div>
                <?php if ($apple->fallenAtFormatted): ?>
                    <div><strong>Упало:</strong> <?= $apple->fallenAtFormatted ?></div>
                <?php endif; ?>
                <?php if ($apple->isFallen && $apple->timeUntilRottenFormatted): ?>
                    <div class="text-warning"><strong>До порчи:</strong> <?= $apple->timeUntilRottenFormatted ?></div>
                <?php endif; ?>
            </div>
            
            <!-- Индикатор размера -->
            <div class="mt-3">
                <div class="d-flex justify-content-between small mb-1">
                    <span>Размер:</span>
                    <span><strong><?= number_format($apple->size * 100, 0) ?>%</strong></span>
                </div>
                <div class="apple-size-bar">
                    <div class="apple-size-fill" style="width: <?= $apple->size * 100 ?>%; background-color: <?= $apple->colorHex ?>"></div>
                </div>
                <?php if ($apple->eaten_percent > 0): ?>
                    <div class="small text-muted mt-1">Съедено: <?= number_format($apple->eaten_percent, 1) ?>%</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Кнопки действий -->
        <div class="card-footer bg-transparent border-top-0">
            <div class="d-grid gap-2">
                <?php if ($apple->isOnTree): ?>
                    <!-- Яблоко на дереве - можно только уронить -->
                    <?= Html::beginForm(['fall', 'id' => $apple->id], 'post') ?>
                        <?= Html::submitButton('⬇️ Уронить', ['class' => 'btn btn-warning btn-sm w-100']) ?>
                    <?= Html::endForm() ?>
                    
                    <button class="btn btn-secondary btn-sm" disabled title="Нельзя съесть яблоко на дереве">
                        🚫 Съесть (на дереве)
                    </button>
                    
                <?php elseif ($apple->isFallen): ?>
                    <!-- Яблоко на земле - можно съесть -->
                    <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-flex gap-1']) ?>
                        <?= Html::input('number', 'percent', 25, [
                            'class' => 'form-control form-control-sm',
                            'min' => 1,
                            'max' => 100,
                            'style' => 'width: 70px',
                            'title' => 'Процент от оставшегося'
                        ]) ?>
                        <?= Html::submitButton('🍴 Съесть %', ['class' => 'btn btn-success btn-sm flex-grow-1']) ?>
                    <?= Html::endForm() ?>
                    
                    <!-- Быстрые кнопки -->
                    <div class="btn-group btn-group-sm w-100">
                        <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::hiddenInput('percent', 25) ?>
                            <?= Html::submitButton('25%', ['class' => 'btn btn-outline-success']) ?>
                        <?= Html::endForm() ?>
                        <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::hiddenInput('percent', 50) ?>
                            <?= Html::submitButton('50%', ['class' => 'btn btn-outline-success']) ?>
                        <?= Html::endForm() ?>
                        <?= Html::beginForm(['eat', 'id' => $apple->id], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::hiddenInput('percent', 100) ?>
                            <?= Html::submitButton('100%', ['class' => 'btn btn-outline-success']) ?>
                        <?= Html::endForm() ?>
                    </div>
                    
                <?php else: ?>
                    <!-- Яблоко гнилое -->
                    <button class="btn btn-secondary btn-sm" disabled>
                        🤢 Несъедобно
                    </button>
                <?php endif; ?>
                
                <!-- Кнопка удаления -->
                <?= Html::beginForm(['delete', 'id' => $apple->id], 'post', [
                    'data-confirm' => 'Удалить это яблоко?'
                ]) ?>
                    <?= Html::submitButton('🗑️ Удалить', ['class' => 'btn btn-outline-danger btn-sm w-100']) ?>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>
</div>

