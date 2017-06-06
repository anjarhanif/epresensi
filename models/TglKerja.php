<?php

namespace app\models;

use Yii;
use app\models\JenisJamkerja;

/**
 * This is the model class for table "tgl_kerja".
 *
 * @property integer $id
 * @property integer $id_jenis
 * @property string $label
 * @property string $tgl_awal
 * @property string $tgl_akhir
 */
class TglKerja extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tgl_kerja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_jenis', 'label', 'tgl_awal', 'tgl_akhir'], 'required'],
            [['id_jenis'], 'integer'],
            [['tgl_awal', 'tgl_akhir'], 'safe'],
            [['label'], 'string', 'max' => 100],
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
            'label' => 'Label',
            'tgl_awal' => 'Tgl Awal',
            'tgl_akhir' => 'Tgl Akhir',
        ];
    }
    
    public function getJamkerja() {
        return $this->hasMany(JamKerja::className(), ['id_jenis'=>'id_jenis']);
    }
    
    public function getJenisJamkerja() {
        return $this->hasOne(JenisJamkerja::className(), ['id'=>'id_jenis']);
    }
}
