<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use app\models\ReportForm;
use app\models\Departments;
use app\models\PermissionHelpers;
use app\models\Report;

class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class'=> AccessControl::className(),
                'only'=>['index','day-report','resume-report','resume-report2'],
                'rules'=>[
                    [
                        'actions'=>['index','day-report','resume-report','resume-report2'],
                        'allow'=>TRUE,
                        'roles'=>['@'],
                        'matchCallback' => function ($rule, $action) {
                            return PermissionHelpers::requireMinimumRole('ReportUser') &&
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
        
    }
      
    public function actionEselon3List() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != NULL) {
                $skpdID = $parents[0];
                $out = Departments::find()->Where(['supdeptid' => $skpdID])
                        ->select(['DeptID as id', 'DeptName as name'])->asArray()->all();
                $params = NULL;
                if(!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                    $selected = $params[0];
                }
               
                echo Json::encode(['output' => $out, 'selected' =>$selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionEselon4List() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $eselon3_id = empty($parents[0]) ? NULL : $parents[0];
            
            if ($eselon3_id != NULL) {
                $out = Departments::find()->where(['supdeptid' => $eselon3_id])
                        ->select(['DeptID as id', 'DeptName as name'])->asArray()->all();
                
                $params=NULL;
                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                    $selected = $params[0];
                }
                
                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function actionDayReport() {
        $model = new ReportForm();
        $report = new Report;
        
        $model->load(Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayDayReport($model),
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
        $report = new Report();
        
        $model->load(\Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayResumeReport($model),
            'pagination'=> [
                'pageSize'=>20,
            ]
        ]);
        
        return $this->render('report-resume', [
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
    }
        

    public function actionRepdayExcel(array $params) {
        $model = new ReportForm;
        $report = new Report();
        
        $model->tglAwal = $params['tglAwal'];
        $model->skpd = $params['skpd'];
        $model->eselon3 = $params['eselon3'];
        $model->eselon4 = $params['eselon4'];
               
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayDayReport($model),
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
                    ->setCellValue('B'.$baseRow, (int)$absen['badgenumber'])
                    ->setCellValue('C'.$baseRow, $absen['name'])
                    ->setCellValue('D'.$baseRow, $absen['datang'])
                    ->setCellValue('E'.$baseRow, $absen['pulang'])
                    ->setCellValue('F'.$baseRow, $absen['keterangan']);
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
        $report = new Report();
        
        $model->tglAwal = $params['tglAwal'];
        $model->skpd = $params['skpd'];
        $model->eselon3 = $params['eselon3'];
        $model->eselon4 = $params['eselon4'];
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayDayReport($model),
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
        $report = new Report();
        
        $model->tglAwal=$params['tglAwal'];
        $model->tglAkhir=$params['tglAkhir'];
        $model->skpd=$params['skpd'];
        $model->eselon3 = $params['eselon3'];
        $model->eselon4 = $params['eselon4'];
               
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayResumeReport($model),
            'pagination'=> ['pageSize'=>FALSE]
        ]);
        
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        //set template
        $template = Yii::getAlias('@app/views/report').'/_resumerep.xlsx';
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
        header('Content-Disposition: attachment;filename="_resumerep.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
    public function actionRepresumePdf(array $params) {
        $model = new ReportForm;
        $report = new Report();
        
        $model->tglAwal = $params['tglAwal'];
        $model->tglAkhir = $params['tglAkhir'];
        $model->skpd = $params['skpd'];
        $model->eselon3 = $params['eselon3'];
        $model->eselon4 = $params['eselon4'];
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayResumeReport($model),
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
    
    public function actionResumeReport2() {
        $model = new ReportForm();
        $report = new Report();
        
        $model->load(\Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayResumeReport2($model),
            'pagination'=> [
                'pageSize'=>20,
            ]
        ]);
        
        return $this->render('report-resume2', [
            'model'=>$model,
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionRepresumeExcel2 (array $params) {
        $model = new ReportForm;
        $report = new Report();
        
        $model->tglAwal=$params['tglAwal'];
        $model->tglAkhir=$params['tglAkhir'];
        $model->skpd=$params['skpd'];
        $model->eselon3 = $params['eselon3'];
        $model->eselon4 = $params['eselon4'];
        
        $dept = Departments::find()->where(['DeptID'=>$model->skpd])->one();
               
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayResumeReport2 ($model),
            'pagination'=> ['pageSize'=>FALSE]
        ]);
        
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        //set template
        $template = Yii::getAlias('@app/views/report').'/_resumerep2.xlsx';
        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();
        // set orientasi dan ukuran kertas
        $activeSheet->getPageSetup()
                ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
        
        $activeSheet->setCellValue('A1','SKPD : '.$dept->DeptName);
        $activeSheet->setCellValue('A2','Periode : '.$model->tglAwal.' - '.$model->tglAkhir);
        
        $tglAwal = new \DateTime($model->tglAwal);
        $tglAkhir = new \DateTime($model->tglAkhir);
        $col = 'D';
        for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {
            $activeSheet->setCellValue($col.'4',$x->format('d'));
            ++$col;
        }
        $activeSheet->setCellValue($col++.'4','H')->setCellValue($col++.'4','S')
            ->setCellValue($col++.'4','I')->setCellValue($col++.'4','C')
            ->setCellValue($col++.'4','TD')->setCellValue($col.'4','TK');
        
        $baseRow=5;
        foreach ($dataProvider->getModels() as $absen) {
            $activeSheet->setCellValue('A'.$baseRow, $baseRow-4)
                    ->setCellValue('B'.$baseRow, (int)$absen['pin'])
                    ->setCellValue('C'.$baseRow, $absen['name']);
            
            $tglAwal = new \DateTime($model->tglAwal);
            $tglAkhir = new \DateTime($model->tglAkhir);
            $col = 'D';
            for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {
                $activeSheet->setCellValue($col.$baseRow, $absen[$x->format('Y-m-d')]);
                ++$col;
            }
            $activeSheet->setCellValue($col++.$baseRow, $absen['hadir'])
                ->setCellValue($col++.$baseRow,$absen['sakit'])
                ->setCellValue($col++.$baseRow,$absen['ijin'])
                ->setCellValue($col++.$baseRow,$absen['cuti'])
                ->setCellValue($col++.$baseRow,$absen['tugas_dinas'])
                ->setCellValue($col.$baseRow,$absen['tampa_keterangan']);
            $baseRow++;
        }
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="_resumerep2.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
    public function actionRepresumePdf2 (array $params) {
        $model = new ReportForm;
        $report = new Report();
        
        $model->tglAwal = $params['tglAwal'];
        $model->tglAkhir = $params['tglAkhir'];
        $model->skpd = $params['skpd'];
        $model->eselon3 = $params['eselon3'];
        $model->eselon4 = $params['eselon4'];
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $report->arrayResumeReport2 ($model),
            'pagination'=> ['pageSize'=>FALSE]
        ]);
        
        $html = $this->renderPartial('_resumerep2', ['dataProvider'=>$dataProvider, 'model'=>$model]);
        
        $mpdf = new \mPDF('c','A4-L','','',0,0,0,0,0,0);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output();

        exit;
    }   
}
