<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "userinfo".
 *
 * @property integer $userid
 * @property string $badgenumber
 * @property integer $defaultdeptid
 * @property string $name
 * @property string $Password
 * @property string $Card
 * @property integer $Privilege
 * @property integer $AccGroup
 * @property string $TimeZones
 * @property string $Gender
 * @property string $Birthday
 * @property string $street
 * @property string $zip
 * @property string $ophone
 * @property string $FPHONE
 * @property string $pager
 * @property string $minzu
 * @property string $title
 * @property string $SN
 * @property string $SSN
 * @property string $UTime
 * @property string $State
 * @property string $City
 * @property integer $SECURITYFLAGS
 * @property integer $DelTag
 * @property integer $RegisterOT
 * @property integer $AutoSchPlan
 * @property integer $MinAutoSchInterval
 * @property integer $Image_id
 */
class Userinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['defaultdeptid', 'Privilege', 'AccGroup', 'SECURITYFLAGS', 'DelTag', 'RegisterOT', 'AutoSchPlan', 'MinAutoSchInterval', 'Image_id'], 'integer'],
            [['badgenumber', 'DelTag','Birthday', 'UTime'], 'safe'],
            [['badgenumber', 'Password', 'Card', 'TimeZones', 'ophone', 'FPHONE', 'pager', 'title', 'SN', 'SSN'], 'string', 'max' => 20],
            [['name', 'street'], 'string', 'max' => 40],
            [['Gender', 'State', 'City'], 'string', 'max' => 2],
            [['zip'], 'string', 'max' => 6],
            [['minzu'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'userid' => 'User Id',
            'badgenumber' => 'PIN',
            'defaultdeptid' => 'Unit Kerja',
            'name' => 'Nama',
            //'Password' => 'Password',
            'Card' => 'NIP',
            //'Privilege' => 'Privilege',
            //'AccGroup' => 'Acc Group',
            //'TimeZones' => 'Time Zones',
            'Gender' => 'Gender',
            'Birthday' => 'Tgl Lahir',
            'street' => 'Jalan',
            'zip' => 'Kode Pos',
            'ophone' => 'Ophone',
            'FPHONE' => 'Fphone',
            //'pager' => 'Pager',
            //'minzu' => 'Minzu',
            'title' => 'Jabatan',
            //'SN' => 'Sn',
            //'SSN' => 'Ssn',
            //'UTime' => 'Utime',
            'State' => 'State',
            'City' => 'City',
            //'SECURITYFLAGS' => 'Securityflags',
            //'DelTag' => 'Del Tag',
            //'RegisterOT' => 'Register Ot',
            //'AutoSchPlan' => 'Auto Sch Plan',
            //'MinAutoSchInterval' => 'Min Auto Sch Interval',
            //'Image_id' => 'Image ID',
        ];
    }
    
    public function getDepartment() {
        return $this->hasOne(Departments::className(), ['DeptID' => 'defaultdeptid']);
    }
    
    public function getCheckinouts() {
        return $this->hasMany(Checkinout::className(), ['userid' => 'userid']);
    }
    
    public function getCheckinoutsDaily() {
        return $this->hasMany(CheckinoutDaily::className(), ['userid'=>'userid']);
            
    }
    
    public function getKeteranganAbsen() {
        return $this->hasMany(KeteranganAbsen::className(), ['userid'=>'userid']);
    }
    
    public static function getUserinfoList($deptids) {
        $droptions = Userinfo::find()->select('userid, name')
                ->where(['IN','defaultdeptid',$deptids])
                ->orderBy('name')->asArray()->all();
        return ArrayHelper::map($droptions, 'userid', 'name');
    }
}
