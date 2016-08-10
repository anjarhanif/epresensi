<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jam_kerja".
 *
 * @property integer $id
 * @property string $nama_jamker
 * @property string $jam_masuk
 * @property string $jam_pulang
 * @property string $mulai_cekin
 * @property string $akhir_cekout
 */
class JamKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jam_kerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_jamker', 'jam_masuk', 'jam_pulang', 'mulai_cekin', 'akhir_cekout'], 'required'],
            [['jam_masuk', 'jam_pulang', 'mulai_cekin', 'akhir_cekout'], 'safe'],
            [['nama_jamker'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_jamker' => 'Nama Jamker',
            'jam_masuk' => 'Jam Masuk',
            'jam_pulang' => 'Jam Pulang',
            'mulai_cekin' => 'Mulai Cekin',
            'akhir_cekout' => 'Akhir Cekout',
        ];
    }
}
