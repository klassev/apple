<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Базовый контроллер для backend
 * Все контроллеры backend должны наследоваться от этого класса
 * для обеспечения защиты авторизацией по умолчанию
 */
class BaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Только авторизованные пользователи
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        // Редирект на страницу входа если пользователь не авторизован
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->loginRequired();
            return false;
        }
        
        return parent::beforeAction($action);
    }
}

