<?php

namespace app\models;

use Yii;
use app\models\JenisJamkerja;

/**
 * This is the model class for table "jam_kerja".
 *
 * @property integer $id
 * @property integer $id_jenis
 * @property string $no_hari
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
            [['id_jenis', 'no_hari', 'jam_masuk', 'jam_pulang', 'mulai_cekin', 'akhir_cekout'], 'required'],
            [['id_jenis'], 'integer'],
            [['jam_masuk', 'jam_pulang', 'mulai_cekin', 'akhir_cekout'], 'safe'],
            [['no_hari'], 'string', 'max' => 7],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_jenis' => 'Id Jenis',
            'no_hari' => 'No Hari',
            'jam_masuk' => 'Jam Masuk',
            'jam_pulang' => 'Jam Pulang',
            'mulai_cekin' => 'Mulai Cekin',
            'akhir_cekout' => 'Akhir Cekout',
        ];
    }
    
    public function getJenisJamkerja() {
        return $this->hasOne(JenisJamkerja::className(), ['id'=>'id_jenis']);
    }
}
