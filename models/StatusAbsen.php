<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "status_absen".
 *
 * @property string $id
 * @property string $status
 * @property string $keterangan
 */
class StatusAbsen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status_absen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'keterangan'], 'required'],
            [['id'], 'string', 'max' => 2],
            [['status'], 'string', 'max' => 25],
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
            'status' => 'Status',
            'keterangan' => 'Keterangan',
        ];
    }
    
    public static function statusAbsenList() {
        $droption = StatusAbsen::find()->asArray()->all();
        return ArrayHelper::map($droption, 'id', 'status');
    }
}
