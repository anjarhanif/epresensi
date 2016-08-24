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
            'DeptID' => 'ID Unit Kerja',
            'DeptName' => 'Nama Unit Kerja',
            'supdeptid' => 'Unit Kerja Induk',
        ];
    }
    
    public function getUserinfos() {
        return $this->hasMany(Userinfo::className(), ['defaultdeptid' => 'DeptID']);
    }
    
    public static function deptList($supdeptid, $deptid='') {
        $droptions = Departments::find()->filterWhere(['supdeptid' => $supdeptid, 'DeptID'=>$deptid])->asArray()->all();
        
        return ArrayHelper::map($droptions, 'DeptID', 'DeptName');
        
    }
    
    public static function getDeptids ($skpdid) {
        $skpd = [$skpdid];
        $eselon3s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                ->bindValue(':deptid', $skpd)->queryAll();
        if(count($eselon3s)) {
            foreach ($eselon3s as $eselon3 ) {
                $eselon4s[] = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                ->bindValue(':deptid', $eselon3['DeptID'])->queryAll();
            }
            $deptids = array_merge_recursive($skpd, $eselon3s, $eselon4s);
        } else $deptids = $skpd;
        
        $deptids = implode(",", $deptids);
        
        return $deptids;
    }
    
    public static function getDeptidNames ($skpdid) {
        
    }
    
}
