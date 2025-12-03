<?php

use yii\db\Migration;

/**
 * Миграция для создания таблицы яблок
 */
class m251203_174843_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string(50)->notNull()->comment('Цвет яблока'),
            'created_at' => $this->integer()->notNull()->comment('Дата появления (unix timestamp)'),
            'fallen_at' => $this->integer()->null()->comment('Дата падения (unix timestamp)'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Статус: 0-на дереве, 1-упало, 2-гнилое'),
            'eaten_percent' => $this->decimal(5, 2)->notNull()->defaultValue(0)->comment('Процент съеденной части'),
        ]);

        // Индекс для быстрой фильтрации по статусу
        $this->createIndex('idx-apple-status', '{{%apple}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
