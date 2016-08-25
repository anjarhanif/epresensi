<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\data\ArrayDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\PermissionHelpers;
use app\models\Departments;
use app\models\Userinfo;

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
        $formatter = \Yii::$app->formatter;
        $attskpds = Departments::find()->where(['supdeptid'=>1])->asArray()->all();
        $allModels = [];
        $series = [];
        foreach ($attskpds as $attskpd) {
            $deptids = Departments::getDeptids($attskpd['DeptID']);
            $usrattds = Userinfo::find()->with(['checkinoutsDaily'=> function($query) {
                        $query->where("DATE(datang) = '2016-7-18'");
                    }])
                    ->where('defaultdeptid IN (:deptids)',[':deptids'=>$deptids])->all();
            $jmlPeg = count($usrattds);
            $jmlHadir = 0;
            foreach ($usrattds as $usrattd) {
                $jmlHadir = $jmlHadir + count($usrattd->checkinoutsDaily);
            }
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
        ]);
        
        return $this->render('index',['dataProvider'=>$dataProvider, 'series'=>$series]);
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
