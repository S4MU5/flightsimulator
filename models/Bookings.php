<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bookings".
 *
 * @property int $id
 * @property int $bort_id
 * @property int $user_id
 * @property int $status
 * @property string $start_date
 * @property string $end_date
 * @property string|null $comments
 * @property string $purpose
 *
 * @property Borts $bort
 * @property Users $user
 */
class Bookings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bort_id', 'user_id', 'status', 'start_date', 'end_date', 'purpose'], 'required'],
            [['bort_id', 'user_id', 'status'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['comments', 'purpose'], 'string', 'max' => 255],
            [['bort_id'], 'exist', 'skipOnError' => true, 'targetClass' => Borts::class, 'targetAttribute' => ['bort_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bort_id' => 'Bort ID',
            'user_id' => 'User ID',
            'status' => 'Статус бронирования',
            'start_date' => 'Вермя вылета',
            'end_date' => 'Время прилета',
            'comments' => 'Комментарий',
            'purpose' => 'Цель вылета',
        ];
    }

    /**
     * Gets query for [[Bort]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBort()
    {
        return $this->hasOne(Borts::class, ['id' => 'bort_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
