<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "issues".
 *
 * @property int $id
 * @property int $bort_id
 * @property string $issue
 * @property string $answer
 *
 * @property Borts $bort
 */
class Issues extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'issues';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bort_id', 'issue', 'answer'], 'required'],
            [['bort_id'], 'integer'],
            [['issue', 'answer'], 'string', 'max' => 255],
            [['bort_id'], 'exist', 'skipOnError' => true, 'targetClass' => Borts::class, 'targetAttribute' => ['bort_id' => 'id']],
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
            'issue' => 'Issue',
            'answer' => 'Answer',
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
}
