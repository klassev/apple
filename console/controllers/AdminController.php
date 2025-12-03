<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Управление администраторами
 */
class AdminController extends Controller
{
    /**
     * Создание администратора
     * Использование: php yii admin/create <username> <email> <password>
     * 
     * @param string $username Имя пользователя
     * @param string $email Email
     * @param string $password Пароль
     * @return int Exit code
     */
    public function actionCreate($username, $email, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        if ($user->save()) {
            $this->stdout("Администратор '{$username}' успешно создан!\n");
            return ExitCode::OK;
        }

        $this->stderr("Ошибка создания администратора:\n");
        foreach ($user->errors as $attribute => $errors) {
            foreach ($errors as $error) {
                $this->stderr("  - {$attribute}: {$error}\n");
            }
        }
        return ExitCode::UNSPECIFIED_ERROR;
    }

    /**
     * Список всех администраторов
     */
    public function actionList()
    {
        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
        
        if (empty($users)) {
            $this->stdout("Администраторы не найдены.\n");
            return ExitCode::OK;
        }

        $this->stdout("Список администраторов:\n");
        $this->stdout(str_repeat('-', 60) . "\n");
        $this->stdout(sprintf("%-5s %-20s %-30s\n", 'ID', 'Username', 'Email'));
        $this->stdout(str_repeat('-', 60) . "\n");
        
        foreach ($users as $user) {
            $this->stdout(sprintf("%-5d %-20s %-30s\n", $user->id, $user->username, $user->email));
        }
        
        return ExitCode::OK;
    }

    /**
     * Смена пароля администратора
     * 
     * @param string $username Имя пользователя
     * @param string $newPassword Новый пароль
     * @return int Exit code
     */
    public function actionChangePassword($username, $newPassword)
    {
        $user = User::findByUsername($username);
        
        if (!$user) {
            $this->stderr("Пользователь '{$username}' не найден.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $user->setPassword($newPassword);
        
        if ($user->save()) {
            $this->stdout("Пароль для '{$username}' успешно изменён!\n");
            return ExitCode::OK;
        }

        $this->stderr("Ошибка смены пароля.\n");
        return ExitCode::UNSPECIFIED_ERROR;
    }
}

