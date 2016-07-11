<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "departments".
 *
 * @property integer $DeptID
 * @property string $DeptName
 * @property integer $supdeptid
 */
class Departments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DeptID', 'DeptName'], 'required'],
            [['DeptID', 'supdeptid'], 'integer'],
            [['DeptName'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'DeptID' => 'Dept ID',
            'DeptName' => 'Dept Name',
            'supdeptid' => 'Supdeptid',
        ];
    }
    
}
