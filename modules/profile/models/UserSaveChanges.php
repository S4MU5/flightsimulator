<?php

namespace app\modules\profile\models;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class UserSaveChanges extends Model
{
    public $first_name;
    public $last_name;
    public $patronymic;
    public $id;
//    public $password;
//    public $old_password;
//    public $password_twice;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
//            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            [['patronymic', 'first_name', 'last_name'], 'string']
        ];
    }

    public function changeUserSet(){
        $user = User::findOne($this->id);
        if (!$user){
            return null;
        }
        $user->last_name = $this->last_name;
        $user->first_name = $this->first_name;
        $user->patronymic = $this->patronymic;
//        $user->HashPassword($this->password);
        if (!$user->save()){
            throw new BadRequestHttpException("Ошибка сохранения элемента");
        }
    }
}
