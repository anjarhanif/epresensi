<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use app\models\Userinfo;
use app\models\Checkinout;

/**
 * Description of ReportSearch
 *
 * @author yasrul
 */
class ReportSearch extends ArrayDataProvider {
    //put your code here
    public function init() {
        $query = Userinfo::find()->with('checkinouts');
        
        foreach ($query->all() as $usercheck) {
            $count = count($usercheck->checkinouts);
            if ($count) {
                if ($count==1) {
                    $datang = $usercheck->checkinouts->checktime;
                    $pulang = NULL;
                } else {
                    $datang = $query->min('checktime');
                    $pulang = $query->max('checktime');
                }
                
            }else {
                $datang = NULL;
                $pulang = NULL;
            }
            
            $this->allModels[] = [
                'userid'=>$usercheck->userid,
                'name'=>$usercheck->name,
                'datang'=>$datang,
                'pulang'=>$pulang,
            ];
                
        }
    }
}
