<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCss('
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        min-height: 100vh;
    }
    
    .navbar-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        padding: 12px 0;
    }
    
    .navbar-custom .navbar-brand {
        font-weight: 700;
        font-size: 1.3rem;
        color: white !important;
    }
    
    .navbar-custom .nav-link {
        color: rgba(255,255,255,0.85) !important;
        font-weight: 500;
        padding: 8px 16px !important;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin: 0 2px;
    }
    
    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link.active {
        color: white !important;
        background: rgba(255,255,255,0.15);
    }
    
    .navbar-custom .btn-logout {
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: 20px;
        padding: 6px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .navbar-custom .btn-logout:hover {
        background: rgba(255,255,255,0.25);
        border-color: rgba(255,255,255,0.5);
        color: white;
    }
    
    .navbar-custom .user-name {
        color: rgba(255,255,255,0.9);
        margin-right: 15px;
        font-weight: 500;
    }
    
    .main-content {
        padding: 30px 0;
    }
    
    .breadcrumb {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
    }
    
    .footer-custom {
        background: white;
        border-top: 1px solid #e9ecef;
        padding: 20px 0;
        margin-top: auto;
    }
    
    .footer-custom p {
        margin: 0;
        color: #6c757d;
    }
');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - <?= Html::encode(Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => '🍎 ' . Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark navbar-custom fixed-top',
        ],
    ]);
    
    $menuItems = [
        ['label' => '🏠 Главная', 'url' => ['/site/index']],
        ['label' => '🌳 Яблоки', 'url' => ['/apple/index']],
    ];
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    
    if (!Yii::$app->user->isGuest) {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex align-items-center'])
            . Html::tag('span', '👤 ' . Yii::$app->user->identity->username, ['class' => 'user-name d-none d-md-inline'])
            . Html::submitButton('Выйти', ['class' => 'btn btn-logout'])
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0 main-content">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'],
                'homeLink' => ['label' => '🏠 Главная', 'url' => ['/site/index']],
            ]) ?>
        <?php endif; ?>
        
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer-custom mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <p><?= Yii::powered() ?></p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
