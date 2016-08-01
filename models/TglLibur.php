<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tgl_libur".
 *
 * @property integer $id
 * @property string $tgl_awal
 * @property string $tgl_akhir
 * @property string $keterangan
 */
class TglLibur extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tgl_libur';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tgl_awal'], 'required'],
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
            'tgl_awal' => 'Tgl Awal',
            'tgl_akhir' => 'Tgl Akhir',
            'keterangan' => 'Keterangan',
        ];
    }
}
