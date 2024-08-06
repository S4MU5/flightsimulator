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
class UserNewPassword extends Model
{
    public $id;
    public $password;
    public $old_password;
    public $password_twice;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'old_password', 'password_twice'], 'required'],
            ['password_twice', 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новый пароль',
            'password_twice' => 'Повторить новый пароль',
            'old_password' => 'Старый пароль'
        ];
    }

    public function changeUserPass(){
        $user = User::findOne($this->id);
        if (!$user){
            return null;
        }
        if ($user->getAttribute('password') == Yii::$app->security->generatePasswordHash($this->password)){
            $user->HashPassword($this->password);
            if (!$user->save()){
                throw new BadRequestHttpException("Ошибка сохранения элемента");
            }
        } else {
            throw new BadRequestHttpException("Неправильно введен старый пароль");
        }
    }
}
