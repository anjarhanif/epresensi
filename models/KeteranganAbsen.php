<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "keterangan_absen".
 *
 * @property integer $id
 * @property integer $userid
 * @property string $statusid
 * @property string $tgl_awal
 * @property string $tgl_akhir
 * @property string $keterangan
 */
class KeteranganAbsen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keterangan_absen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'statusid', 'tgl_awal'], 'required'],
            [['userid'], 'integer'],
            [['tgl_awal', 'tgl_akhir'], 'safe'],
            [['statusid'], 'string', 'max' => 2],
            [['keterangan'], 'string', 'max' => 255],
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
            'statusid' => 'Statusid',
            'tgl_awal' => 'Tgl Awal',
            'tgl_akhir' => 'Tgl Akhir',
            'keterangan' => 'Keterangan',
        ];
    }
    
    public function getUserinfo() {
        return $this->hasOne(Userinfo::className(), ['userid'=>'userid']);
    }
}
