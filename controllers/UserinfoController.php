<?php

namespace app\controllers;

use Yii;
use app\models\Userinfo;
use app\models\Departments;
use app\models\search\UserinfoSearch;
use app\models\PermissionHelpers;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserinfoController implements the CRUD actions for Userinfo model.
 */
class UserinfoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class'=> AccessControl::className(),
                'only'=>['index','update','create','view','delete'],
                'rules'=>[
                    [
                        'actions'=>['index','create','update','view','delete'],
                        'allow'=>TRUE,
                        'roles'=>['@'],
                        'matchCallback' => function ($rule, $action) {
                            return PermissionHelpers::requireMinimumRole('AdminSystem') &&
                            PermissionHelpers::requireStatus('Active');
                        }
                    ],
                    [
                        'actions'=>['index','create','update','view','delete'],
                        'allow'=>TRUE,
                        'roles'=>['@'],
                        'matchCallback' => function ($rule, $action) {
                            return PermissionHelpers::requireMinimumRole('AdminSKPD') &&
                            PermissionHelpers::requireStatus('Active');
                        }
                    ],      
                ],
                'denyCallback'=> function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('Anda tidak diizinkan untuk mengakses halaman '.$action->id.' ini');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Userinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        //if (PermissionHelpers::requireMinimumRole('AdminSKPD')) {
            $deptid = Yii::$app->user->identity->dept_id;
            $deptids = Departments::getDeptids($deptid);
        //} else $deptids=NULL;
        
        $searchModel = new UserinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('defaultdeptid IN (:deptids)',[':deptids'=>$deptids]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Userinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Userinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Userinfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->userid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Userinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $deptid = Yii::$app->user->identity->dept_id;
        $deptids = explode(",", Departments::getDeptids($deptid)) ;
        
        $model = $this->findModel($id);

        if ( in_array($model->defaultdeptid,$deptids)) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->userid]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return FALSE;
        }

    }

    /**
     * Deletes an existing Userinfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Userinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Userinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Userinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
