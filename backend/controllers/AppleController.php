<?php

namespace backend\controllers;

use common\models\Apple;
use Yii;
use yii\base\InvalidCallException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Контроллер для управления яблоками
 */
class AppleController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'generate' => ['POST'],
                    'fall' => ['POST'],
                    'eat' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Список всех яблок
     * 
     * @return string
     */
    public function actionIndex()
    {
        $apples = Apple::find()->orderBy(['status' => SORT_ASC, 'created_at' => SORT_DESC])->all();
        
        // Группируем яблоки по статусу
        $groupedApples = [
            Apple::STATUS_ON_TREE => [],
            Apple::STATUS_FALLEN => [],
            Apple::STATUS_ROTTEN => [],
        ];
        
        foreach ($apples as $apple) {
            $groupedApples[$apple->status][] = $apple;
        }
        
        return $this->render('index', [
            'apples' => $apples,
            'groupedApples' => $groupedApples,
        ]);
    }

    /**
     * Сгенерировать случайные яблоки
     * 
     * @return Response
     */
    public function actionGenerate()
    {
        $count = Yii::$app->request->post('count', rand(3, 10));
        $count = min(max((int)$count, 1), 50); // От 1 до 50
        
        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            $apple = Apple::createRandom();
            if ($apple->save()) {
                $created++;
            }
        }
        
        Yii::$app->session->setFlash('success', "Создано яблок: {$created}");
        
        return $this->redirect(['index']);
    }

    /**
     * Яблоко падает на землю
     * 
     * @param int $id
     * @return Response
     */
    public function actionFall($id)
    {
        $apple = $this->findModel($id);
        
        try {
            $apple->fallToGround();
            Yii::$app->session->setFlash('success', 'Яблоко упало на землю');
        } catch (InvalidCallException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Съесть часть яблока
     * 
     * @param int $id
     * @return Response
     */
    public function actionEat($id)
    {
        $apple = $this->findModel($id);
        $percent = Yii::$app->request->post('percent', 25);
        $percent = min(max((float)$percent, 1), 100); // От 1% до 100%
        
        try {
            $deleted = $apple->eat($percent);
            
            if ($deleted) {
                Yii::$app->session->setFlash('success', 'Яблоко полностью съедено и удалено');
            } else {
                Yii::$app->session->setFlash('success', "Откушено {$percent}% от оставшегося яблока. Осталось: " . ($apple->size * 100) . '%');
            }
        } catch (InvalidCallException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Удалить яблоко
     * 
     * @param int $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $apple = $this->findModel($id);
        $apple->delete();
        
        Yii::$app->session->setFlash('success', 'Яблоко удалено');
        
        return $this->redirect(['index']);
    }

    /**
     * Удалить все яблоки
     * 
     * @return Response
     */
    public function actionDeleteAll()
    {
        Apple::deleteAll();
        
        Yii::$app->session->setFlash('success', 'Все яблоки удалены');
        
        return $this->redirect(['index']);
    }

    /**
     * Найти модель по ID
     * 
     * @param int $id
     * @return Apple
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Яблоко не найдено');
    }
}

