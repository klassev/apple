<?php

/** @var yii\web\View $this */
/** @var common\models\Apple[] $apples */
/** @var array $groupedApples */

use common\models\Apple;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;

$this->title = 'Управление яблоками';
$this->params['breadcrumbs'][] = $this->title;

// Регистрируем CSS стили
$this->registerCss('
    .apple-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 15px;
        overflow: hidden;
    }
    .apple-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .apple-icon {
        font-size: 64px;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    .apple-on-tree {
        animation: swing 2s ease-in-out infinite;
    }
    .apple-rotten {
        filter: grayscale(50%) brightness(0.7);
    }
    @keyframes swing {
        0%, 100% { transform: rotate(-3deg); }
        50% { transform: rotate(3deg); }
    }
    .apple-size-bar {
        height: 10px;
        border-radius: 5px;
        background: #e9ecef;
        overflow: hidden;
    }
    .apple-size-fill {
        height: 100%;
        border-radius: 5px;
        transition: width 0.3s;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .section-header {
        border-left: 4px solid;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    .section-on-tree { border-color: #27ae60; }
    .section-fallen { border-color: #f39c12; }
    .section-rotten { border-color: #e74c3c; }
');
?>

<div class="apple-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::beginForm(['generate'], 'post', ['class' => 'd-inline-flex align-items-center gap-2']) ?>
                <?= Html::input('number', 'count', rand(3, 10), [
                    'class' => 'form-control form-control-sm',
                    'style' => 'width: 80px',
                    'min' => 1,
                    'max' => 50,
                    'placeholder' => 'Кол-во'
                ]) ?>
                <?= Html::submitButton('🌳 Вырастить яблоки', ['class' => 'btn btn-success']) ?>
            <?= Html::endForm() ?>
            
            <?php if (!empty($apples)): ?>
                <?= Html::beginForm(['delete-all'], 'post', [
                    'class' => 'd-inline ms-2',
                    'data-confirm' => 'Удалить все яблоки?'
                ]) ?>
                    <?= Html::submitButton('🗑️ Удалить все', ['class' => 'btn btn-outline-danger']) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($apples)): ?>
        <div class="alert alert-info text-center py-5">
            <h4>🍎 Яблок пока нет</h4>
            <p class="mb-0">Нажмите кнопку "Вырастить яблоки" чтобы создать случайные яблоки</p>
        </div>
    <?php else: ?>
        <!-- Статистика -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0"><?= count($groupedApples[Apple::STATUS_ON_TREE]) ?></h3>
                        <small>На дереве</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h3 class="mb-0"><?= count($groupedApples[Apple::STATUS_FALLEN]) ?></h3>
                        <small>На земле</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0"><?= count($groupedApples[Apple::STATUS_ROTTEN]) ?></h3>
                        <small>Гнилые</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0"><?= count($apples) ?></h3>
                        <small>Всего</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Яблоки на дереве -->
        <?php if (!empty($groupedApples[Apple::STATUS_ON_TREE])): ?>
            <h4 class="section-header section-on-tree">🌳 На дереве</h4>
            <div class="row mb-4">
                <?php foreach ($groupedApples[Apple::STATUS_ON_TREE] as $apple): ?>
                    <?= $this->render('_apple_card', ['apple' => $apple]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Упавшие яблоки -->
        <?php if (!empty($groupedApples[Apple::STATUS_FALLEN])): ?>
            <h4 class="section-header section-fallen">🍂 На земле</h4>
            <div class="row mb-4">
                <?php foreach ($groupedApples[Apple::STATUS_FALLEN] as $apple): ?>
                    <?= $this->render('_apple_card', ['apple' => $apple]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Гнилые яблоки -->
        <?php if (!empty($groupedApples[Apple::STATUS_ROTTEN])): ?>
            <h4 class="section-header section-rotten">🦠 Гнилые</h4>
            <div class="row mb-4">
                <?php foreach ($groupedApples[Apple::STATUS_ROTTEN] as $apple): ?>
                    <?= $this->render('_apple_card', ['apple' => $apple]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

