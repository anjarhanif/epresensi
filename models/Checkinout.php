<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checkinout".
 *
 * @property integer $id
 * @property integer $userid
 * @property string $checktime
 * @property string $checktype
 * @property integer $verifycode
 * @property string $SN
 * @property string $sensorid
 * @property string $WorkCode
 * @property string $Reserved
 */
class Checkinout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checkinout';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'checktime', 'checktype', 'verifycode'], 'required'],
            [['userid', 'verifycode'], 'integer'],
            [['checktime'], 'safe'],
            [['checktype'], 'string', 'max' => 1],
            [['SN', 'WorkCode', 'Reserved'], 'string', 'max' => 20],
            [['sensorid'], 'string', 'max' => 5],
            [['userid', 'checktime'], 'unique', 'targetAttribute' => ['userid', 'checktime'], 'message' => 'The combination of Userid and Checktime has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'checktime' => 'Checktime',
            'checktype' => 'Checktype',
            'verifycode' => 'Verifycode',
            'SN' => 'Sn',
            'sensorid' => 'Sensorid',
            'WorkCode' => 'Work Code',
            'Reserved' => 'Reserved',
        ];
    }
    
    public function getUserinfo() {
        return $this->hasOne(Userinfo::className(), ['userid' => 'userid']);
    }
    
    public function getDevice() {
        return $this->hasOne(Iclock::className(), ['SN' => 'SN']);
    }
}
