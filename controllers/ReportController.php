<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
        $dataProvider = new ReportSearch();
        
        return $this->render('report', [
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
    }

}
