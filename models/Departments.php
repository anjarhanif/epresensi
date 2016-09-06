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
    
    public function getSupdept() {
        return $this->hasOne(Departments::className(), ['DeptID'=>'supdeptid'])->from(Departments::tableName().' sup_dept');
    }
    
    public function getSubdepts() {
        return $this->hasMany(Departments::className(), ['supdeptid'=>'DeptID'])->from(Departments::tableName().' sub_dept');
    }

    public function getUserinfos() {
        return $this->hasMany(Userinfo::className(), ['defaultdeptid' => 'DeptID']);
    }
    
    public static function deptList($supdeptid, $deptid='') {
        $droptions = Departments::find()->filterWhere(['supdeptid' => $supdeptid, 'DeptID'=>$deptid])->asArray()->all();
        
        return ArrayHelper::map($droptions, 'DeptID', 'DeptName');
        
    }
    
    public static function getDeptids ($skpdid) {
        $deptids = [$skpdid];
        $eselon3s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                ->bindValue(':deptid', $skpdid)->queryAll();            
        if(count($eselon3s)) {
            $deptids = array_merge($deptids, array_column($eselon3s,'DeptID'));
            foreach ($eselon3s as $eselon3 ) {
                $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                ->bindValue(':deptid', $eselon3['DeptID'])->queryAll();
                if(count($eselon4s)) {
                    $deptids = array_merge($deptids, array_column($eselon4s,'DeptID'));
                }             
            }
        }       
        return $deptids;
    }
    
    public static function getDeptidNames ($skpdid) {
        $depts = [];
        $depts[] = Yii::$app->db->createCommand('select DeptID, DeptName from departments where DeptID =:deptid')
                ->bindValue(':deptid', $skpdid)->queryOne();
        $eselon3s = Yii::$app->db->createCommand('select DeptID, DeptName from departments where supdeptid =:deptid')
                ->bindValue(':deptid', $skpdid)->queryAll();
        if(count($eselon3s)) {
            foreach ($eselon3s as $eselon3) {
                $depts[] = $eselon3;
                $eselon4s = Yii::$app->db->createCommand('select DeptID, DeptName from departments where supdeptid =:deptid')
                ->bindValue(':deptid', $eselon3['DeptID'])->queryAll();
                if(count($eselon4s)) {
                    foreach ($eselon4s as $eselon4) {
                        $depts[] = $eselon4;
                    }
                }
            }
        }
        return ArrayHelper::map($depts, 'DeptID', 'DeptName');
    }
    
}
