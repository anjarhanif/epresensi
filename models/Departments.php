<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Userinfo;

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
    
    public function getUserinfos() {
        return $this->hasMany(Userinfo::className(), ['defaultdeptid' => 'DeptID']);
    }
    
    public static function deptList($supdeptid = 0) {
        $droption = Departments::find()->where(['supdeptid'=>$supdeptid])->asArray()->all();
        return ArrayHelper::map($droption, 'DeptID', 'DeptName');
    }
    
}
