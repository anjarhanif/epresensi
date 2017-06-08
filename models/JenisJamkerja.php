<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "jenis_jamkerja".
 *
 * @property integer $id
 * @property string $nama_jenis
 * @property string $keterangan
 */
class JenisJamkerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_jamkerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_jenis'], 'required'],
            [['nama_jenis'], 'string', 'max' => 100],
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
            'nama_jenis' => 'Nama Jenis',
            'keterangan' => 'Keterangan',
        ];
    }
    
    public static function getListJenisJamkerja() {
        $droptions = JenisJamkerja::find()->all();
        return ArrayHelper::map($droptions, 'id', 'nama_jenis');
    }
}
