<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\Departments;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','logout','signup'],
                'rules' => [
                    [
                        'actions' => ['index','logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','signup'],
                        'allow' => true,
                        'roles' => ['?'],
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $attskpds = Departments::find()->where(['supdeptid'=>1])->asArray()->all();
        $allModels = [];
        $series = [];
        foreach ($attskpds as $attskpd) {
            $deptids = Departments::getDeptids($attskpd['DeptID']);
            //$deptids = implode(",", $deptids);
            
            
            //$jmlPeg = Yii::$app->db->createCommand('select count(userid) from userinfo where defaultdeptid IN (:deptids)')
            //        ->bindValue(':deptids', $deptids)->queryScalar();
            $q = new Query();
            $jmlPeg = $q->select('count(userid)')->from('userinfo')
                    ->where(['IN','defaultdeptid', $deptids])
                    ->count();
            
            /*
            $query = 'select count(distinct u.userid) from userinfo u '
                    . 'inner join checkinout c on u.userid = c.userid and DATE(c.checktime) = CURDATE() '
                    . 'where u.defaultdeptid IN (:deptids) ';
            
            $jmlHadir = Yii::$app->db->createCommand($query)->bindValues([':deptids'=>$deptids])
                    ->queryScalar();*/
            $q = new Query();
            $jmlHadir = $q->select('count(distinct u.userid)')->from('userinfo u')
                    ->innerJoin('checkinout c','u.userid = c.userid and DATE(c.checktime) = "2016-8-8"')
                    ->where(['IN','u.defaultdeptid', $deptids])
                    ->scalar();
            
            $persen = $jmlPeg != 0 ? round(($jmlHadir/$jmlPeg) * 100, 2) : 0;

            $allModels[] = [
                'skpd' => $attskpd['DeptName'],
                'jmlpeg' => $jmlPeg,
                'jmlhadir' => $jmlHadir,
                '%hadir'=> $persen 
            ];
            $series[] = [
                'name'=>$attskpd['DeptName'],
                'data'=> [$persen],
            ];
        }
        $dataProvider = new ArrayDataProvider([
            'allModels'=> $allModels,
            'pagination'=> [
                'pageSize'=>20,
            ],
            'sort'=>[
                'attributes'=>['skpd','jmlpeg','jmlhadir','%hadir'],
                'defaultOrder'=>['%hadir'=>SORT_DESC],
            ],
        ]);
        //$deptids = Departments::getDeptids(6);
        //$deptids = implode(",", $deptids);
        
        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('index',['dataProvider'=>$dataProvider, 'series'=>$series]);
        } else {
            return $this->render('index',['dataProvider'=>$dataProvider, 'series'=>$series, 'deptids'=>$deptids]);
        }       
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionSignup() {
        $model = new SignupForm();
        
        if ($model->load(Yii::$app->request->post())) {
            //var_dump($model);
            //return "test";
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', ['model'=>$model]);
    }
}
