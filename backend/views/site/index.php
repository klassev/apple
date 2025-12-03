<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Главная';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-light p-5 rounded-3 mb-4">
        <h1 class="display-4">Панель управления</h1>
        <p class="lead">Добро пожаловать, <?= Html::encode(Yii::$app->user->identity->username) ?>!</p>
        <hr class="my-4">
        <p>Вы успешно вошли в административную панель.</p>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Информация о системе</h5>
                        <p class="card-text">
                            <strong>PHP версия:</strong> <?= phpversion() ?><br>
                            <strong>Yii версия:</strong> <?= Yii::getVersion() ?><br>
                            <strong>Текущее время:</strong> <?= date('d.m.Y H:i:s') ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Быстрые действия</h5>
                        <p class="card-text">Здесь будут отображаться быстрые действия для управления сайтом.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Статистика</h5>
                        <p class="card-text">Здесь будет отображаться статистика сайта.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
