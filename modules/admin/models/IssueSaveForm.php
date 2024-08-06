<?php

namespace app\modules\admin\models;

use app\models\Issues;
use Yii;
use yii\base\Model;
use yii\grid\ActionColumn;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

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
class IssueSaveForm extends Model
{

    public $issue;
    public $answer;
    public $id;
    public $bort_id;
    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return 'issuesaveform';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['issue', 'answer'], 'required'],
            [['issue'], 'string', 'max' => 255],
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
            'issue' => 'Вопрос',
            'answer' => 'Ответ',
        ];
    }

    public function save() {
        if ($this->id){
            $issue = Issues::findOne($this->id);
            if (!$issue){
                throw new NotFoundHttpException("Элемент не найден");
            }
        } else {
            $issue = new Issues();
        }

        $issue->setAttribute('bort_id', $this->bort_id);
        $issue->setAttribute("issue", $this->issue);
        $issue->setAttribute("answer", json_encode($this->answer, JSON_UNESCAPED_UNICODE));
        $issue->setAttribute('active', 1);

        if (!$issue->save()){
            throw new BadRequestHttpException("Ошибка сохранения элемента");
        }

    }


}
