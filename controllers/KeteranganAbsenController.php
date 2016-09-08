<?php

namespace app\controllers;

use Yii;
use app\models\KeteranganAbsen;
use app\models\search\KeteranganAbsenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PermissionHelpers;
use app\models\Departments;

/**
 * KeteranganAbsenController implements the CRUD actions for KeteranganAbsen model.
 */
class KeteranganAbsenController extends Controller
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
                    ]
                    
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
     * Lists all KeteranganAbsen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $deptid = Yii::$app->user->identity->dept_id;
        $deptids = Departments::getDeptids($deptid);

        $searchModel = new KeteranganAbsenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['IN','userinfo.defaultdeptid',$deptids]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KeteranganAbsen model.
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
     * Creates a new KeteranganAbsen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KeteranganAbsen();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing KeteranganAbsen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $deptid = Yii::$app->user->identity->dept_id;
        $deptids = Departments::getDeptids($deptid);
        
        $model = $this->findModel($id);      
        
        if (in_array($model->userinfo->defaultdeptid,$deptids)) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else return FALSE;    
    }

    /**
     * Deletes an existing KeteranganAbsen model.
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
     * Finds the KeteranganAbsen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KeteranganAbsen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KeteranganAbsen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionExportExcel(array $params) {
        $deptid = Yii::$app->user->identity->dept_id;
        $deptids = Departments::getDeptids($deptid);

        $searchModel = new KeteranganAbsenSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->query->andFilterWhere(['IN','userinfo.defaultdeptid',$deptids]);
        $dataProvider->pagination=FALSE;
        
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        //set template
        $template = Yii::getAlias('@app/views/keterangan-absen').'/_export.xlsx';
        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();
        // set orientasi dan ukuran kertas
        $activeSheet->getPageSetup()
                ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
        
        $baseRow=3;
        foreach ($dataProvider->getModels() as $absen) {
            $activeSheet->setCellValue('A'.$baseRow, $baseRow-2)
                    ->setCellValue('B'.$baseRow, $absen->userinfo->name)
                    ->setCellValue('C'.$baseRow, $absen->statusid)
                    ->setCellValue('D'.$baseRow, $absen->tgl_awal)
                    ->setCellValue('E'.$baseRow, $absen->tgl_akhir)
                    ->setCellValue('F'.$baseRow, $absen->keterangan);
            $baseRow++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="_export.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
    public function actionExportPdf(array $params) {
        $deptid = Yii::$app->user->identity->dept_id;
        $deptids = Departments::getDeptids($deptid);

        $searchModel = new KeteranganAbsenSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->query->andFilterWhere(['IN','userinfo.defaultdeptid',$deptids]);
        $dataProvider->pagination = FALSE;
        
        $html = $this->renderPartial('export-pdf', ['dataProvider'=>$dataProvider]);
        
        $mpdf = new \mPDF('c', 'A4','','',0,0,0,0,0,0);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        
        exit;
    }
}
