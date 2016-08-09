<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;

use app\models\ReportForm;
use app\models\Checkinout;
use app\models\search\ReportSearch;
use app\models\Departments;
use app\models\Userinfo;
use app\models\KeteranganAbsen;

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
        
        //$dataProvider = $this->searchDayReport($model);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayDayReport($model),
            'pagination'=> [
                'pageSize'=>30,
            ]
        ]);
        
        return $this->render('report-day', [
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
        
    }
    
    public function actionResumeReport() {
        $model = new ReportForm();
        $model->load(\Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayResumeReport($model),
            'pagination'=> [
                'pageSize'=>20,
            ]
        ]);
        
        return $this->render('report-resume', [
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function arrayDayReport($model) {
        $deptid = null;
        if (isset($model->skpd)) $deptid=$model->skpd;
        if (isset($model->eselon3)) $deptid=$model->eselon3;
        if (isset($model->eselon4)) $deptid=$model->eselon4;
        
        $query = 'SELECT u.userid, u.name, IF(COUNT(c.checktime) > 0, MIN(c.checktime),"Nihil" ) AS Datang, '.
                'IF(COUNT(c.checktime) > 1, MAX(c.checktime),"Nihil" ) AS Pulang, '.
                'IF(k.statusid IS NULL, IF(TIME(MIN(c.checktime)) > "07:30:59" OR TIME(MAX(c.checktime)) < "16:00:00", "K",""), k.statusid) AS Keterangan '.
                'FROM userinfo u '.
                'LEFT JOIN checkinout c ON u.userid=c.userid AND DATE(c.checktime)=:tgl '.
                'LEFT JOIN keterangan_absen k ON u.userid=k.userid AND :tgl BETWEEN k.tgl_awal AND (IF(k.tgl_akhir IS NULL, k.tgl_awal, k.tgl_akhir)) '.
                'WHERE u.defaultdeptid =:deptid '.
                'GROUP BY u.userid, DATE(c.checktime) '.
                'ORDER BY u.userid ASC ';
        
        $cmd = Yii::$app->db->createCommand($query);
        $cmd->bindValues([':tgl'=>$model->tglAwal, ':deptid'=>$deptid]);
        
        return $cmd->queryAll();               
    }
    
    public function arrayResumeReport($model) {
        $deptid = null;
        if (isset($model->skpd)) $deptid=$model->skpd;
        if (isset($model->eselon3)) $deptid=$model->eselon3;
        if (isset($model->eselon4)) $deptid=$model->eselon4;
        
        $renAwal = new \DateTime($model->tglAwal);
        $renAkhir = new \DateTime($model->tglAkhir);
        
        $query = Userinfo::find()->with('keteranganAbsen', 'checkinoutsDaily')
                ->where(['defaultdeptid'=>$deptid])
                ->all();
        
        $allModels = [];
        
        foreach ($query as $userInfo) {
            $jmlSakit = 0;
            $jmlIjin =0;
            $jmlTD=0;
            $jmlCuti=0;
            $jmlAlpa=0;
            $jmlTHCP=0;
            
            if(count($userInfo->keteranganAbsen)) {
                foreach ($userInfo->keteranganAbsen as $ketAbsen) {
                    
                    if ($ketAbsen->tgl_akhir == NULL) {
                        $tglAkhir = new \DateTime($ketAbsen->tgl_awal);
                    } else $tglAkhir = new \DateTime($ketAbsen->tgl_akhir);                                     
                    $tglAwal = new \DateTime($ketAbsen->tgl_awal);
                    
                    
                    if (($tglAwal >= $renAwal AND $tglAwal <= $renAkhir) OR ($tglAkhir <= $renAkhir AND $tglAkhir >= $renAwal)) {
                        if ($tglAwal < $renAwal) $tglAwal = $renAwal;
                        if ($tglAkhir > $renAkhir) $tglAkhir =$renAkhir;
                        
                        if($ketAbsen->statusid == 'S') {                                                   
                            $jmlSakit = $jmlSakit + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                        } elseif ($ketAbsen->statusid == 'I') {                                                      
                            $jmlIjin = $jmlIjin + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                        } elseif ($ketAbsen->statusid == 'TD') {                                                     
                            $jmlTD = $jmlTD + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                        } elseif ($ketAbsen->statusid == 'C') {                                                      
                            $jmlCuti = $jmlCuti + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                        }
                    }                   
                }               
            }
            if (count($userInfo->checkinoutsDaily)) {
                foreach ($userInfo->checkinoutsDaily as $checkinout) {
                    
                    $tglDatang = new \DateTime($checkinout->datang);
                    if ($checkinout->pulang == NULL) {
                        $tglPulang = $tglDatang;
                    }else $tglPulang = new \DateTime($checkinout->pulang);
                    
                    
                    if ($tglDatang->format('Y-m-d') >= $renAwal->format('Y-m-d') AND $tglDatang->format('Y-m-d') <= $renAkhir->format('Y-m-d')) {
                        $tglAwal = new \DateTime($userInfo->keteranganAbsen->tgl_awal);
                        $tglAkhir = new \DateTime($userInfo->keteranganAbsen->tgl_akhir);
                    
                        $Benar = KeteranganAbsen::find()->where(['userid'=>$userInfo->userid])
                            ->andWhere(['<=', 'tgl_awal', $tglDatang->format('Y-m-d')])
                            ->andWhere(['>=', 'tgl_akhir', $tglDatang->format('Y-m-d')])
                            ->one();
                    
                        if (! $Benar) {
                            if (  $tglDatang->format('H:i:s') > '07:30:59' OR $tglPulang->format('H:i:s') < '16:00:00') {
                            $jmlTHCP = $jmlTHCP +1;
                            }
                        }
                    }                    
                }
            } 
            $allModels[]=[
                'userid'=>$userInfo->userid,
                'name'=>$userInfo->name,
                'sakit'=>$jmlSakit,
                'ijin'=>$jmlIjin,
                'tugas-dinas'=>$jmlTD,
                'cuti'=>$jmlCuti,
                'th-cp'=>$jmlTHCP,
                'alpa'=>$jmlAlpa,
            ];
        }
        return $allModels;
    }

    public function actionRepdayExcel(array $params) {
        $model = new ReportForm;
        $model->tglAwal=$params['tglAwal'];
        $model->skpd=$params['skpd'];
               
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayDayReport($model),
            'pagination'=> [
                'pageSize'=>FALSE,
            ]
        ]);
        
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
    
    public function actionRepdayPdf(array $params) {
        $model = new ReportForm;
        $model->tglAwal = $params['tglAwal'];
        $model->skpd = $params['skpd'];
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayDayReport($model),
            'pagination'=> [
                'pageSize'=>FALSE,
            ]
        ]);
        
        $html = $this->renderPartial('_dayrep', ['dataProvider'=>$dataProvider]);
        
        $mpdf = new \mPDF('c', 'A4','','',0,0,0,0,0,0);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        
        exit;
    }
    
        public function actionRepresumeExcel(array $params) {
        $model = new ReportForm;
        $model->tglAwal=$params['tglAwal'];
        $model->tglAkhir=$params['tglAkhir'];
        $model->skpd=$params['skpd'];
               
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayResumeReport($model),
            'pagination'=> ['pageSize'=>FALSE]
        ]);
        
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
                    ->setCellValue('D'.$baseRow, $absen['sakit'])
                    ->setCellValue('E'.$baseRow, $absen['ijin'])
                    ->setCellValue('F'.$baseRow, $absen['tugas-dinas'])
                    ->setCellValue('G'.$baseRow, $absen['cuti'])
                    ->setCellValue('H'.$baseRow, $absen['th-cp'])
                    ->setCellValue('E'.$baseRow, $absen['alpa']);
            $baseRow++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="_dayrep.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
        public function actionRepresumePdf(array $params) {
        $model = new ReportForm;
        $model->tglAwal = $params['tglAwal'];
        $model->tglAkhir = $params['tglAkhir'];
        $model->skpd = $params['skpd'];
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayResumeReport($model),
            'pagination'=> ['pageSize'=>FALSE]
        ]);
        
        $html = $this->renderPartial('_resumerep', ['dataProvider'=>$dataProvider]);
        
        $mpdf = new \mPDF('c','A4','','',0,0,0,0,0,0);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output();

        exit;
    }

}
