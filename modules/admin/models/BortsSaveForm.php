<?php

namespace app\modules\admin\models;

use app\models\Borts;
use Yii;
use yii\base\Model;
use yii\helpers\StringHelper;
use yii\web\BadRequestHttpException;

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
class BortsSaveForm extends Model
{
    /**
     * {@inheritdoc}
     */
    public $name;
    public $number;

    public function formName()
    {
        return 'bortssaveform';
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
            'name' => 'Название',
            'number' => 'Регистрационный номер',
        ];
    }

    public function save() {
        $bort = new Borts();
        $bort->setAttributes([
            'name' => $this->name,
            'number' => $this->number
        ]);

        if (!$bort->save()){
            throw new BadRequestHttpException("Ошибка сохранения элемента");
        }
    }
}
