<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "borts".
 *
 * @property int $id
 * @property string $name
 * @property string $number
 *
 * @property Bookings[] $bookings
 * @property Issues[] $issues
 */
class Borts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'borts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'number'], 'required'],
            [['name', 'number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'number' => 'Number',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Bookings::class, ['bort_id' => 'id']);
    }

    /**
     * Gets query for [[Issues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIssues()
    {
        return $this->hasMany(Issues::class, ['bort_id' => 'id']);
    }

    public function getExploits()
    {
        return $this->hasMany(Exploitation::class, ['bort_id' => 'id']);
    }

    public function getLastExploit()
    {
        return Exploitation::find()
            ->where(['bort_id' => $this->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }
}
