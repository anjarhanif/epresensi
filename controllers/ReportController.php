<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use yii\helpers\Json;

use app\models\ReportForm;
use app\models\Checkinout;
use app\models\search\ReportSearch;
use app\models\Departments;

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
    
    public function actionDeptOptions($id) {
        $count = Departments::find()->where(['supdeptid' => $id])->count();
        
        $depts = Departments::find()->where(['supdeptid' => $id])->all();
        if ($count > 0) {
            foreach ($depts as $dept) {
                echo "<option value='".$dept->DeptID."'>".$dept->DeptName."</option>";
            }
        } else {
            echo "<option>-</option>";
        }
    }
    
    public function actionEselon3List() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != NULL) {
                $skpdID = $parents[0];
                $out = Departments::find()->where(['supdeptid' => $skpdID])
                        ->select(['DeptID as id', 'DeptName as name'])->asArray()->all();
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }
    
    public function actionEselon4List() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $skpd_id = empty($ids[0]) ? NULL : $ids[0] ;
            $eselon3_id = empty($ids[1]) ? NULL : $ids[1];
            
            if ($eselon3_id != NULL) {
                $out = Departments::find()->where(['supdeptid' => $eselon3_id])
                        ->select(['DeptID as id', 'DeptName as name'])->asArray()->all();
                
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionDayReport() {
        $model = new ReportForm(); 
        $model->load(Yii::$app->request->queryParams);
        
        $dataProvider = $this->searchDayReport($model);
        
        return $this->render('report', [
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
        
    }
    
    public function searchDayReport($model) {
        
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
        
        return $dataProvider;
    }
    public function actionExportExcel(array $params) {
        $model = new ReportForm;
        $model->tgl=$params['tgl'];
        $model->skpd=$params['skpd'];
        
        $dataProvider = $this->searchDayReport($model);
        
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        //set template
        $template = Yii::getAlias('@app/views/report').'/_dayrep.xlsx';
        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();
        // set orientasi dan ukuran kertas
        $activeSheet->getPageSetup()
                ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
        
        $baseRow=3;
        foreach ($dataProvider->getModels() as $absen) {
            $activeSheet->setCellValue('A'.$baseRow, $baseRow-2)
                    ->setCellValue('B'.$baseRow, $absen['userid'])
                    ->setCellValue('C'.$baseRow, $absen['name'])
                    ->setCellValue('D'.$baseRow, $absen['Datang'])
                    ->setCellValue('E'.$baseRow, $absen['Pulang']);
            $baseRow++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="_dayrep.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
    public function actionExportPdf(array $params) {
        $model = new ReportForm;
        $model->tgl = $params['tgl'];
        $model->skpd = $params['skpd'];
        
        $dataProvider = $this->searchDayReport($model);
        $html = $this->renderPartial('_dayrep', ['dataProvider'=>$dataProvider]);
        
        $mpdf = new \mPDF('c', 'A4','','',0,0,0,0,0,0);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        
        exit;
    }

}
