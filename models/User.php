<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

/**
 * @property int id
 * @property string username
 * @property string password
 * @property string first_name
 * @property string last_name
 * @property string father_name
 * @property string authKey
 * @property string accessToken
 * @property string patronymic
 */

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName(){
        return 'users';
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_repeat' => 'Повторный пароль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patronymic' => 'Отчество'
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function banUser($id){
        date_default_timezone_set('Asia/Novosibirsk');
        $user = User::findOne($id);
        $d = new \DateTime();
        $user->setAttribute('ban_time', $d->modify('+1 hour')->format('Y-m-d H:i:s'));
        $user->save();
    }

    public static function isBanned($id){
        $user = User::findOne($id);
        return $user->getAttribute('ban_time');
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function HashPassword($password){
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
}
