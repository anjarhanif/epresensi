<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $role_name
 * @property integer $role_value
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_name', 'role_value'], 'required'],
            [['role_value'], 'integer'],
            [['role_name'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_name' => 'Role Name',
            'role_value' => 'Role Value',
        ];
    }
    
    public static function getRoleList() {
        $droption = Role::find()->asArray()->all();
        return ArrayHelper::map($droption, 'id', 'role_name');
    }

        public function getUsers() {
        return $this->hasMany(User::className(), ['rode_id'=>'id']);
    }
}
