<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

$this->registerCss('
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f64f59 100%);
        background-size: 400% 400%;
        animation: gradient 15s ease infinite;
        min-height: 100vh;
    }
    
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    /* Частицы на фоне */
    .particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        z-index: 0;
    }
    
    .particle {
        position: absolute;
        width: 10px;
        height: 10px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        animation: rise 10s infinite ease-in;
    }
    
    .particle:nth-child(1) { left: 10%; animation-duration: 8s; animation-delay: 0s; }
    .particle:nth-child(2) { left: 20%; animation-duration: 12s; animation-delay: 1s; }
    .particle:nth-child(3) { left: 30%; animation-duration: 10s; animation-delay: 2s; }
    .particle:nth-child(4) { left: 40%; animation-duration: 14s; animation-delay: 0.5s; }
    .particle:nth-child(5) { left: 50%; animation-duration: 9s; animation-delay: 3s; }
    .particle:nth-child(6) { left: 60%; animation-duration: 11s; animation-delay: 1.5s; }
    .particle:nth-child(7) { left: 70%; animation-duration: 13s; animation-delay: 2.5s; }
    .particle:nth-child(8) { left: 80%; animation-duration: 8s; animation-delay: 4s; }
    .particle:nth-child(9) { left: 90%; animation-duration: 10s; animation-delay: 0.8s; }
    .particle:nth-child(10) { left: 95%; animation-duration: 12s; animation-delay: 1.2s; }
    
    @keyframes rise {
        0% {
            bottom: -10%;
            transform: translateX(0) scale(1);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            bottom: 110%;
            transform: translateX(100px) scale(0.5);
            opacity: 0;
        }
    }
    
    main {
        position: relative;
        z-index: 1;
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
<body class="d-flex flex-column h-100 justify-content-center">
<?php $this->beginBody() ?>

<!-- Частицы на фоне -->
<div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<main role="main">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
