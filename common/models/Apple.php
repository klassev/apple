<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\InvalidCallException;

/**
 * Модель яблока
 * 
 * @property int $id
 * @property string $color Цвет яблока
 * @property int $created_at Дата появления (unix timestamp)
 * @property int|null $fallen_at Дата падения (unix timestamp)
 * @property int $status Статус яблока
 * @property float $eaten_percent Процент съеденной части
 * 
 * @property float $size Оставшийся размер яблока (1 - eaten_percent/100)
 * @property bool $isOnTree На дереве ли яблоко
 * @property bool $isFallen Упало ли яблоко
 * @property bool $isRotten Гнилое ли яблоко
 * @property string $statusLabel Текстовый статус
 * @property string $colorHex Цвет в HEX формате
 */
class Apple extends ActiveRecord
{
    /**
     * Статусы яблока
     */
    const STATUS_ON_TREE = 0;   // На дереве
    const STATUS_FALLEN = 1;    // Упало
    const STATUS_ROTTEN = 2;    // Гнилое

    /**
     * Время до порчи яблока (5 часов в секундах)
     */
    const ROTTEN_TIME = 5 * 60 * 60;

    /**
     * Доступные цвета яблок
     */
    const COLORS = [
        'red' => '#e74c3c',
        'green' => '#27ae60',
        'yellow' => '#f1c40f',
        'orange' => '#e67e22',
        'pink' => '#fd79a8',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color'], 'required'],
            [['color'], 'string', 'max' => 50],
            [['color'], 'in', 'range' => array_keys(self::COLORS)],
            [['created_at', 'fallen_at', 'status'], 'integer'],
            [['eaten_percent'], 'number', 'min' => 0, 'max' => 100],
            [['status'], 'in', 'range' => [self::STATUS_ON_TREE, self::STATUS_FALLEN, self::STATUS_ROTTEN]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Цвет',
            'created_at' => 'Дата появления',
            'fallen_at' => 'Дата падения',
            'status' => 'Статус',
            'eaten_percent' => 'Съедено (%)',
        ];
    }

    /**
     * Создание нового яблока с случайными параметрами
     * 
     * @param string|null $color Цвет (если не указан - случайный)
     * @return Apple
     */
    public static function createRandom(?string $color = null): Apple
    {
        $apple = new self();
        
        // Случайный цвет если не указан
        if ($color === null) {
            $colors = array_keys(self::COLORS);
            $color = $colors[array_rand($colors)];
        }
        
        $apple->color = $color;
        
        // Случайная дата появления (от 30 дней назад до текущего момента)
        $apple->created_at = rand(time() - 30 * 24 * 60 * 60, time());
        
        $apple->status = self::STATUS_ON_TREE;
        $apple->eaten_percent = 0;
        
        return $apple;
    }

    /**
     * Яблоко падает на землю
     * 
     * @throws InvalidCallException Если яблоко уже не на дереве
     */
    public function fallToGround(): void
    {
        if (!$this->isOnTree) {
            throw new InvalidCallException('Яблоко уже не на дереве');
        }

        $this->status = self::STATUS_FALLEN;
        $this->fallen_at = time();
        
        if (!$this->save()) {
            throw new \RuntimeException('Не удалось сохранить яблоко: ' . implode(', ', $this->getFirstErrors()));
        }
    }

    /**
     * Съесть часть яблока
     * 
     * @param float $percent Процент от оставшегося яблока
     * @throws InvalidCallException При невозможности съесть
     * @return bool True если яблоко полностью съедено и удалено
     */
    public function eat(float $percent): bool
    {
        // Проверка: яблоко на дереве
        if ($this->isOnTree) {
            throw new InvalidCallException('Съесть нельзя, яблоко на дереве');
        }

        // Проверка: яблоко гнилое
        if ($this->isRotten) {
            throw new InvalidCallException('Съесть нельзя, яблоко гнилое');
        }

        // Проверка: процент должен быть положительным
        if ($percent <= 0) {
            throw new InvalidCallException('Процент должен быть положительным числом');
        }

        // Проверка: процент не больше 100
        if ($percent > 100) {
            throw new InvalidCallException('Нельзя съесть больше 100%');
        }

        // Вычисляем сколько съедается от ВСЕГО яблока
        // percent - это процент от оставшегося яблока
        $remainingPercent = 100 - $this->eaten_percent;
        $actualEaten = ($remainingPercent * $percent) / 100;
        
        $this->eaten_percent += $actualEaten;

        // Если съели полностью (или больше) - удаляем
        if ($this->eaten_percent >= 100) {
            return $this->delete() !== false;
        }

        if (!$this->save()) {
            throw new \RuntimeException('Не удалось сохранить яблоко: ' . implode(', ', $this->getFirstErrors()));
        }

        return false;
    }

    /**
     * Проверить и обновить статус порчи
     * Вызывается автоматически при получении статуса
     */
    public function checkRottenStatus(): void
    {
        if ($this->status === self::STATUS_FALLEN && $this->fallen_at !== null) {
            $hoursOnGround = time() - $this->fallen_at;
            
            if ($hoursOnGround >= self::ROTTEN_TIME) {
                $this->status = self::STATUS_ROTTEN;
                $this->save(false, ['status']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // Автоматически проверяем порчу при загрузке из БД
        $this->checkRottenStatus();
    }

    // ==================== ГЕТТЕРЫ ====================

    /**
     * Получить оставшийся размер яблока (от 0 до 1)
     * 
     * @return float
     */
    public function getSize(): float
    {
        return round((100 - $this->eaten_percent) / 100, 2);
    }

    /**
     * На дереве ли яблоко
     * 
     * @return bool
     */
    public function getIsOnTree(): bool
    {
        return $this->status === self::STATUS_ON_TREE;
    }

    /**
     * Упало ли яблоко
     * 
     * @return bool
     */
    public function getIsFallen(): bool
    {
        return $this->status === self::STATUS_FALLEN;
    }

    /**
     * Гнилое ли яблоко
     * 
     * @return bool
     */
    public function getIsRotten(): bool
    {
        return $this->status === self::STATUS_ROTTEN;
    }

    /**
     * Получить текстовый статус
     * 
     * @return string
     */
    public function getStatusLabel(): string
    {
        return self::getStatusLabels()[$this->status] ?? 'Неизвестно';
    }

    /**
     * Получить все статусы с метками
     * 
     * @return array
     */
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_ON_TREE => 'На дереве',
            self::STATUS_FALLEN => 'На земле',
            self::STATUS_ROTTEN => 'Гнилое',
        ];
    }

    /**
     * Получить цвет в HEX формате
     * 
     * @return string
     */
    public function getColorHex(): string
    {
        return self::COLORS[$this->color] ?? '#cccccc';
    }

    /**
     * Получить название цвета на русском
     * 
     * @return string
     */
    public function getColorLabel(): string
    {
        $labels = [
            'red' => 'Красное',
            'green' => 'Зелёное',
            'yellow' => 'Жёлтое',
            'orange' => 'Оранжевое',
            'pink' => 'Розовое',
        ];
        
        return $labels[$this->color] ?? $this->color;
    }

    /**
     * Форматированная дата появления
     * 
     * @return string
     */
    public function getCreatedAtFormatted(): string
    {
        return date('d.m.Y H:i', $this->created_at);
    }

    /**
     * Форматированная дата падения
     * 
     * @return string|null
     */
    public function getFallenAtFormatted(): ?string
    {
        return $this->fallen_at ? date('d.m.Y H:i', $this->fallen_at) : null;
    }

    /**
     * Время до порчи в секундах (или null если не применимо)
     * 
     * @return int|null
     */
    public function getTimeUntilRotten(): ?int
    {
        if ($this->status !== self::STATUS_FALLEN || $this->fallen_at === null) {
            return null;
        }

        $timeOnGround = time() - $this->fallen_at;
        $remaining = self::ROTTEN_TIME - $timeOnGround;
        
        return max(0, $remaining);
    }

    /**
     * Форматированное время до порчи
     * 
     * @return string|null
     */
    public function getTimeUntilRottenFormatted(): ?string
    {
        $seconds = $this->getTimeUntilRotten();
        
        if ($seconds === null) {
            return null;
        }
        
        if ($seconds <= 0) {
            return 'Уже испорчено';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($hours > 0) {
            return "{$hours} ч. {$minutes} мин.";
        }
        
        return "{$minutes} мин.";
    }
}

