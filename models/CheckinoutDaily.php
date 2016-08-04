<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checkinout_daily".
 *
 * @property integer $userid
 * @property string $datang
 * @property string $pulang
 */
class CheckinoutDaily extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'checkinout_daily';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid'], 'required'],
            [['userid'], 'integer'],
            [['datang', 'pulang'], 'string', 'max' => 19],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => 'Userid',
            'datang' => 'Datang',
            'pulang' => 'Pulang',
        ];
    }
}
