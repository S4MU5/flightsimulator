<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $first_name;
    public $patronymic;
    public $last_name;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'first_name', 'last_name'], 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Пользователь с таким ником существует'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['patronymic', 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'password_repeat' => 'Повторный пароль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patronymic' => 'Отчество'
       ];
    }

    public function registerUser(){
        if (!$this->validate()){
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->last_name = $this->last_name;
        $user->first_name = $this->first_name;
        $user->patronymic = $this->patronymic;
        $user->HashPassword($this->password);
        $user->save();
        $role = Yii::$app->authManager->getRole('user');
        Yii::$app->authManager->assign($role, $user->id);
        return $user;
    }
}
