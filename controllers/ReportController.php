<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\models\ReportForm;
use app\models\Checkinout;
use app\models\search\ReportSearch;

class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionDayReport() {
        $model = new ReportForm();
        
        $model->load(Yii::$app->request->queryParams);
        
        $deptid = null;
        if (isset($model->skpd)) $deptid=$model->skpd;
        if (isset($model->eselon3)) $deptid=$model->eselon3;
        if (isset($model->eselon4)) $deptid=$model->eselon4;
        
        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM userinfo')->queryScalar();
        
        $dataProvider = new SqlDataProvider([
            'sql' => 'SELECT u.userid, u.name, IF(COUNT(c.checktime) > 0, MIN(c.checktime), "Nihil") AS Datang, '.
                'IF(COUNT(c.checktime) > 1, MAX(c.checktime), "Nihil") AS Pulang '.
                'FROM userinfo u LEFT JOIN checkinout c ON u.userid=c.userid AND DATE(c.checktime)=:tgl '.
                'WHERE u.defaultdeptid =:deptid '.
                'GROUP BY u.userid, DATE(c.checktime) '.
                'ORDER BY u.userid ASC ',
                //'LIMIT 20',
            'params' => [':tgl'=>$model->tgl, ':deptid'=>$deptid],
            'totalCount' => $count,
            'pagination' => ['pageSize'=>20]
        ]);
        
        return $this->render('report', [
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
        
    }

}
