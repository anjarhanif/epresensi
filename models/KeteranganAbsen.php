<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "keterangan_absen".
 *
 * @property integer $id
 * @property integer $iduser
 * @property integer $idstatus
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
            [['iduser', 'idstatus', 'tgl_awal', 'tgl_akhir', 'keterangan'], 'required'],
            [['iduser', 'idstatus'], 'integer'],
            [['tgl_awal', 'tgl_akhir'], 'safe'],
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
            'iduser' => 'Iduser',
            'idstatus' => 'Idstatus',
            'tgl_awal' => 'Tgl Awal',
            'tgl_akhir' => 'Tgl Akhir',
            'keterangan' => 'Keterangan',
        ];
    }
}
