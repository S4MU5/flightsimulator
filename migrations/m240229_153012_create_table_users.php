<?php

use yii\db\Migration;
use app\models\User;
use app\models\Role;
use app\models\Userrole;

/**
 * Class m240229_153012_create_table_users
 */
class m240229_153012_create_table_users extends Migration
{
    // Перед созданием таблицы сделайте миграцию yii migrate --migrationPath=@yii/rbac/migrations
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(User::tableName(), [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'authKey' => $this->string()->null(),
            'accessToken' => $this->string()->null(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'patronymic' => $this->string()->null(),
            'vlek' => $this->date()->null()
        ]);


        $this->insert(User::tableName(), [
            'id' => 1,
            'first_name' => 'Директор',
            'last_name' => 'Этого',
            'patronymic' => 'Сайта',
            'username' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('qwe123')
        ]);

        $this->insert(User::tableName(), [
            'id' => 2,
            'first_name' => 'Чудов',
            'last_name' => 'Дмитрий',
            'patronymic' => 'Сергеевич',
            'username' => 'Dchudo',
            'password' => Yii::$app->security->generatePasswordHash('qwerty')
        ]);

        $this->insert(User::tableName(), [
            'id' => 3,
            'first_name' => 'Ударцев',
            'last_name' => 'Данил',
            'patronymic' => 'Александрович',
            'username' => 'SAMUS',
            'password' => Yii::$app->security->generatePasswordHash('SAMUS')
        ]);

        $admin = Yii::$app->authManager->createRole('admin');
        $user = Yii::$app->authManager->createRole('user');
        $pilot = Yii::$app->authManager->createRole('pilot');

        $admin->description = 'Администратор';
        $user->description = 'Пользователь';
        $pilot->description = 'Пилот';

        Yii::$app->authManager->add($admin); // Создание двух ролей
        Yii::$app->authManager->add($user);
        Yii::$app->authManager->add($pilot);

        $admin_permit = Yii::$app->authManager->createPermission('canAdmin');
        $user_permit = Yii::$app->authManager->createPermission('canUser');
        $pilot_permit = Yii::$app->authManager->createPermission('canPilot');

        $admin_permit->description = 'Права на админку';
        $user_permit->description = 'Права на пользование';
        $pilot_permit->description = 'Права на аренду';


        Yii::$app->authManager->add($admin_permit); // создание доступов
        Yii::$app->authManager->add($user_permit);
        Yii::$app->authManager->add($pilot_permit);

        $admin_role = Yii::$app->authManager->getRole('admin');
        $user_role = Yii::$app->authManager->getRole('user');
        $pilot_role = Yii::$app->authManager->getRole('pilot');
        $admin_permit = Yii::$app->authManager->getPermission('canAdmin');
        $user_permit = Yii::$app->authManager->getPermission('canUser');
        $pilot_permit = Yii::$app->authManager->getPermission('canPilot');

        Yii::$app->authManager->addChild($admin_role, $admin_permit); // создание зависимостей
        Yii::$app->authManager->addChild($user_role, $user_permit);
        Yii::$app->authManager->addChild($pilot_role, $pilot_permit);
        Yii::$app->authManager->addChild($pilot_permit, $user_permit);
        Yii::$app->authManager->addChild($admin_permit, $user_permit);
        Yii::$app->authManager->addChild($admin_permit, $pilot_permit);

        Yii::$app->authManager->assign($admin_role, 1); // Присваивание юзеру роли
        Yii::$app->authManager->assign($pilot_role, 2);
        Yii::$app->authManager->assign($user_role, 3);
//        Yii::$app->authManager->assign($user_role, Yii::$app->user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(User::tableName());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240229_153012_create_table_users cannot be reverted.\n";

        return false;
    }
    */
}
