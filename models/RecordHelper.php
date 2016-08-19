<?php

namespace app\models;
use yii;

/**
 * Description of RecordHelper
 *
 * @author yasrul
 */
class RecordHelper {
    //put your code here
    public static function userHas($model_name) {
        $connection = \Yii::$app->db;
        $userid = \Yii::$app->user->identity->id;
        
        $sql = "select id from $model_name where user_id=:userid";
        $command = $connection->createCommand($sql);
        $command->bindValue(":userid", $userid);
        $result = $command->queryOne();
        
        if($result == NULL) {
            return false;
        }else {
            return $result['id'];
        }
    }
}
