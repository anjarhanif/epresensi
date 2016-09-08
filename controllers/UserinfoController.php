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
        $deptid = Yii::$app->user->identity->dept_id;
        $deptids = Departments::getDeptids($deptid);
        
        $searchModel = new UserinfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['defaultdeptid'=>$deptids]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
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
        $deptids = Departments::getDeptids($deptid) ;
        
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
    
    public function actionExportExcel(array $params=NULL) {
        $deptid = Yii::$app->user->identity->dept_id;
        $deptids = Departments::getDeptids($deptid);

        $searchModel = new UserinfoSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->query->andFilterWhere(['IN','defaultdeptid',$deptids]);
        $dataProvider->pagination=FALSE;
        
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        //set template
        $template = Yii::getAlias('@app/views/userinfo').'/_export.xlsx';
        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();
        // set orientasi dan ukuran kertas
        $activeSheet->getPageSetup()
                ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
        
        $baseRow=3;
        foreach ($dataProvider->getModels() as $userinfo) {
            $activeSheet->setCellValue('A'.$baseRow, $baseRow-2)
                    ->setCellValue('B'.$baseRow, (int)$userinfo->badgenumber)
                    ->setCellValue('C'.$baseRow, $userinfo->name)
                    ->setCellValue('D'.$baseRow, $userinfo->Card)
                    ->setCellValue('E'.$baseRow, $userinfo->department->DeptName);
            $baseRow++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="_export.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
    public function actionExportPdf(array $params=NULL) {
        $deptid = Yii::$app->user->identity->dept_id;
        $dept = Departments::find()->select('DeptName')->where(['DeptID'=>$deptid])->one();
        $deptids = Departments::getDeptids($deptid);

        $searchModel = new UserinfoSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->query->andFilterWhere(['IN','defaultdeptid',$deptids]);
        $dataProvider->pagination = FALSE;
        
        $html = $this->renderPartial('_export-pdf', ['dataProvider'=>$dataProvider, 'dept'=>$dept]);
        
        $mpdf = new \mPDF('c', 'A4','','',0,0,0,0,0,0);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        
        exit;
    }
}
