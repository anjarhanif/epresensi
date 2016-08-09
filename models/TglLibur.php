<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tgl_libur".
 *
 * @property integer $id
 * @property string $tgl_libur
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
            [['tgl_libur'], 'required'],
            [['tgl_libur'], 'safe'],
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
            'tgl_libur' => 'Tgl Libur',
            'keterangan' => 'Keterangan',
        ];
    }
}
