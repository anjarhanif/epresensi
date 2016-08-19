<?php

namespace app\models;

use yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\ValueHelpers;

/**
 * Description of PermissionHelpers
 *
 * @author yasrul
 */
class PermissionHelpers {
    
    public static function requireStatus ($status_name) 
    {
        return ValueHelpers::statusMatch($status_name);
    }
    
    public static function requireRole($role_name)
    {
        return ValueHelpers::roleMatch($role_name);
    }
    
    public static function requireMinimumRole($role_name, $userId=null) {
        if(ValueHelpers::isRoleNameValid($role_name)) {
            if($userId=NULL) {
                $userRoleValue = ValueHelpers::getUsersRoleValue();
            }else {
                $userRoleValue = ValueHelpers::getUsersRoleValue($userId);
            }
            return $userRoleValue >= ValueHelpers::getRoleValue($role_name) ? TRUE : FALSE;
        } else {
            return FALSE;
        }
    }
    
    public static function userMustBeOwner($model_name, $model_id) {
        $connection = \Yii::$app->db;
        $userid = \Yii::$app->user->identity->id;
        
        $sql = "select id from $model_name"
                . "where user_id=:userid and id=:model_id ";
        $command = $connection->createCommand($sql);
        $command->bindValue(":userid", $userid);
        $command->bindValue(":model_id", $model_id);
        
        if($result = $command->queryOne()) {
            return true;
        }else{
            return false;
        }
    }
}
