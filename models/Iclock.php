<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iclock".
 *
 * @property string $SN
 * @property integer $State
 * @property string $LastActivity
 * @property string $TransTimes
 * @property integer $TransInterval
 * @property string $LogStamp
 * @property string $OpLogStamp
 * @property string $PhotoStamp
 * @property string $Alias
 * @property integer $DeptID
 * @property string $UpdateDB
 * @property string $Style
 * @property string $FWVersion
 * @property integer $FPCount
 * @property integer $TransactionCount
 * @property integer $UserCount
 * @property string $MainTime
 * @property integer $MaxFingerCount
 * @property integer $MaxAttLogCount
 * @property string $DeviceName
 * @property string $AlgVer
 * @property string $FlashSize
 * @property string $FreeFlashSize
 * @property string $Language
 * @property string $VOLUME
 * @property string $DtFmt
 * @property string $IPAddress
 * @property string $IsTFT
 * @property string $Platform
 * @property string $Brightness
 * @property string $BackupDev
 * @property string $OEMVendor
 * @property string $City
 * @property integer $AccFun
 * @property integer $TZAdj
 * @property integer $DelTag
 * @property string $FPVersion
 * @property string $PushVersion
 */
class Iclock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iclock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SN', 'State', 'TransInterval', 'Alias', 'UpdateDB', 'AccFun', 'TZAdj', 'DelTag'], 'required'],
            [['State', 'TransInterval', 'DeptID', 'FPCount', 'TransactionCount', 'UserCount', 'MaxFingerCount', 'MaxAttLogCount', 'AccFun', 'TZAdj', 'DelTag'], 'integer'],
            [['LastActivity'], 'safe'],
            [['SN', 'LogStamp', 'OpLogStamp', 'PhotoStamp', 'Alias', 'Style', 'MainTime', 'IPAddress', 'Platform'], 'string', 'max' => 20],
            [['TransTimes', 'City'], 'string', 'max' => 50],
            [['UpdateDB', 'FlashSize', 'FreeFlashSize', 'VOLUME', 'DtFmt', 'FPVersion', 'PushVersion'], 'string', 'max' => 10],
            [['FWVersion', 'DeviceName', 'AlgVer', 'Language', 'BackupDev', 'OEMVendor'], 'string', 'max' => 30],
            [['IsTFT', 'Brightness'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SN' => 'Sn',
            'State' => 'State',
            'LastActivity' => 'Last Activity',
            'TransTimes' => 'Trans Times',
            'TransInterval' => 'Trans Interval',
            'LogStamp' => 'Log Stamp',
            'OpLogStamp' => 'Op Log Stamp',
            'PhotoStamp' => 'Photo Stamp',
            'Alias' => 'Alias',
            'DeptID' => 'Dept ID',
            'UpdateDB' => 'Update Db',
            'Style' => 'Style',
            'FWVersion' => 'Fwversion',
            'FPCount' => 'Fpcount',
            'TransactionCount' => 'Transaction Count',
            'UserCount' => 'User Count',
            'MainTime' => 'Main Time',
            'MaxFingerCount' => 'Max Finger Count',
            'MaxAttLogCount' => 'Max Att Log Count',
            'DeviceName' => 'Device Name',
            'AlgVer' => 'Alg Ver',
            'FlashSize' => 'Flash Size',
            'FreeFlashSize' => 'Free Flash Size',
            'Language' => 'Language',
            'VOLUME' => 'Volume',
            'DtFmt' => 'Dt Fmt',
            'IPAddress' => 'Ipaddress',
            'IsTFT' => 'Is Tft',
            'Platform' => 'Platform',
            'Brightness' => 'Brightness',
            'BackupDev' => 'Backup Dev',
            'OEMVendor' => 'Oemvendor',
            'City' => 'City',
            'AccFun' => 'Acc Fun',
            'TZAdj' => 'Tzadj',
            'DelTag' => 'Del Tag',
            'FPVersion' => 'Fpversion',
            'PushVersion' => 'Push Version',
        ];
    }
}
