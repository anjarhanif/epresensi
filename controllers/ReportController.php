<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\db\Query;
use app\models\ReportForm;
use app\models\Departments;
use app\models\Userinfo;
use app\models\KeteranganAbsen;
use app\models\TglLibur;
use app\models\JamKerja;
use app\models\PermissionHelpers;

class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class'=> AccessControl::className(),
                'only'=>['index','day-report','resume-report'],
                'rules'=>[
                    [
                        'actions'=>['index','day-report','resume-report'],
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
        $model->load(Yii::$app->request->queryParams);
        
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
        $deptids = [];
        if ($model->eselon4 != NULL) {
            $deptids=[$model->eselon4];
        }elseif ($model->eselon3 != NULL) {
            $eselon3s = [$model->eselon3];
            $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $model->eselon3)->queryAll();
            $deptids = array_merge($eselon3s, array_column($eselon4s,'DeptID'));          
        }elseif ($model->skpd !=NULL) {
            $skpd = [$model->skpd];
            $eselon3s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $model->skpd)->queryAll();
            if(count($eselon3s)) {
                $deptids = array_merge($skpd, array_column($eselon3s,'DeptID'));
                foreach ($eselon3s as $eselon3 ) {
                    $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $eselon3['DeptID'])->queryAll();
                    $deptids = array_merge($deptids, array_column($eselon4s,'DeptID'));
                }
            } else $deptids = $skpd;
        }
               
        $tglAwal = new \DateTime($model->tglAwal);
        if(in_array($tglAwal->format('w'),[1,2,3,4])) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'senin-kamis'])->one();
        }elseif($tglAwal->format('w') == 5) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'jumat'])->one();
        }

       $allModels = (new Query())->select(['u.badgenumber','u.name','IF(COUNT(c.checktime) > 0, MIN(c.checktime),"Nihil") AS datang', 
                'IF(COUNT(c.checktime) > 1, MAX(c.checktime),"Nihil" ) AS pulang', 
                'IF(k.statusid IS NULL, IF(:tgl <> CURDATE(), IF(TIME(MIN(c.checktime)) > :jamMasuk OR TIME(MAX(c.checktime)) < :jamPulang, "TH/CP",IF(COUNT(c.checktime) = 0,"TK","")),""), k.statusid) AS keterangan'])
               ->from('userinfo u')
               ->leftJoin('checkinout c','u.userid=c.userid AND DATE(c.checktime)=:tgl')
               ->leftJoin('keterangan_absen k','u.userid=k.userid AND :tgl BETWEEN k.tgl_awal AND (IF(k.tgl_akhir IS NULL, k.tgl_awal, k.tgl_akhir)) ')
               ->where(['IN','u.defaultdeptid',$deptids])
               ->groupBy('u.userid, DATE(c.checktime)')
               ->orderBy('u.userid ASC')
               ->addParams([':tgl'=>$model->tglAwal,':jamMasuk'=>$jamKerja->jam_masuk,':jamPulang'=>$jamKerja->jam_pulang])
               ->all();
              
        return $allModels;   
    }
    
    public function arrayResumeReport($model) {
        $deptids = [];
        if ($model->eselon4 != NULL) {
            $deptids=[$model->eselon4];
        }elseif ($model->eselon3 != NULL) {
            $eselon3s = [$model->eselon3];
            $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $model->eselon3)->queryAll();
            $deptids = array_merge($eselon3s, array_column($eselon4s,'DeptID'));          
        }elseif ($model->skpd !=NULL) {
            $skpd = [$model->skpd];
            $eselon3s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $model->skpd)->queryAll();
            if(count($eselon3s)) {
                $deptids = array_merge($skpd, array_column($eselon3s,'DeptID'));
                foreach ($eselon3s as $eselon3 ) {
                    $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $eselon3['DeptID'])->queryAll();
                    $deptids = array_merge($deptids, array_column($eselon4s,'DeptID'));
                }
            } else $deptids = $skpd;
        }
        
        $tglAwal = new \DateTime($model->tglAwal);
        if(in_array($tglAwal->format('w'),[1,2,3,4])) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'senin-kamis'])->one();
        }elseif($tglAwal->format('w') == 5) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'jumat'])->one();
        }
        
        $renAwal = $model->tglAwal;
        if($model->tglAkhir == NULL) {
            $renAkhir = $renAwal;
        }else $renAkhir = $model->tglAkhir;   
                
        $query = Userinfo::find()->select('userid, badgenumber, name')->with([
            'keteranganAbsen'=>function($query) use($renAwal, $renAkhir) {
                $query->andWhere('(tgl_awal >= :renAwal and tgl_awal <= :renAkhir) or '
                        . '(tgl_akhir >= :renAwal and tgl_akhir <= :renAkhir)',[':renAwal'=>$renAwal, ':renAkhir'=>$renAkhir]);
            },
            'checkinoutsDaily'=>function ($query) use($renAwal, $renAkhir) {
                $query->andWhere('DATE(datang) >= :renAwal and DATE(datang) <= :renAkhir',[':renAwal'=>$renAwal, ':renAkhir'=>$renAkhir]);
            }
            ])
        ->where(['IN','defaultdeptid', $deptids])
        ->orderBy('userid ASC')
        ->asArray()->all();
        
        $allModels = [];
        
        $renAwal = new \DateTime($model->tglAwal);
        if($model->tglAkhir == NULL) {
            $renAkhir = $renAwal;
        }else $renAkhir = new \DateTime($model->tglAkhir);
        
        foreach ($query as $userInfo) {
            $jmlSakit = 0;
            $jmlIjin =0;
            $jmlTD=0;
            $jmlCuti=0;
            $jmlAlpa=0;
            $jmlTHCP=0;
            
            if(count($userInfo['keteranganAbsen'])) {
                foreach ($userInfo['keteranganAbsen'] as $ketAbsen) {
                    
                    if ($ketAbsen['tgl_akhir'] == NULL) {
                        $tglAkhir = new \DateTime($ketAbsen['tgl_awal']);
                    } else $tglAkhir = new \DateTime($ketAbsen['tgl_akhir']);                                     
                    $tglAwal = new \DateTime($ketAbsen['tgl_awal']);                  
                    
                    if ($tglAwal < $renAwal) $tglAwal = $renAwal;
                    if ($tglAkhir > $renAkhir) $tglAkhir =$renAkhir;
                        
                    if($ketAbsen['statusid'] == 'S') {                                                   
                        $jmlSakit = $jmlSakit + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                    } elseif ($ketAbsen['statusid'] == 'I') {                                                      
                        $jmlIjin = $jmlIjin + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                    } elseif ($ketAbsen['statusid'] == 'TD') {                                                     
                        $jmlTD = $jmlTD + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                    } elseif ($ketAbsen['statusid'] == 'C') {                                                      
                        $jmlCuti = $jmlCuti + $tglAkhir->diff($tglAwal)->format("%a")+1;
                            
                    }                                 
                }               
            }
            if (count($userInfo['checkinoutsDaily'])) {
                foreach ($userInfo['checkinoutsDaily'] as $checkinout) {
                    
                    $tglDatang = new \DateTime($checkinout['datang']);
                    if ($checkinout['pulang'] == NULL) {
                        $tglPulang = $tglDatang;
                    }else $tglPulang = new \DateTime($checkinout['pulang']);
                    
                    if ( ! (TglLibur::find()->where(['tgl_libur'=>$tglDatang->format('Y-m-d')])->one() OR in_array($tglDatang->format('w'),[0,6]))) {
                    
                        $Ada1 = KeteranganAbsen::find()->where(['userid'=>$userInfo->userid])
                                ->andWhere(['tgl_awal'=> $tglDatang->format('Y-m-d')])
                                ->andWhere(['IS', 'tgl_akhir', NULL])
                                ->exists();
                            
                        $Ada2 = KeteranganAbsen::find()->where(['userid'=>$userInfo->userid])
                                ->andWhere(['<=','tgl_awal', $tglDatang->format('Y-m-d')])
                                ->andWhere(['>=','tgl_akhir', $tglDatang->format('Y-m-d')])
                                ->exists();
                    
                        if ( ! ($Ada1 || $Ada2) ) {
                            if ($tglDatang->format('H:i:s') > $jamKerja->jam_masuk OR $tglPulang->format('H:i:s') < $jamKerja->jam_pulang) {
                                $jmlTHCP = $jmlTHCP +1;
                            }
                        }
                    }                    
                }
            } 
            $allModels[]=[
                'userid'=>(int)$userInfo['badgenumber'],
                'name'=>$userInfo['name'],
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
    
    public function actionResumeReport2() {
        $model = new ReportForm();
        $model->load(\Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayResumeReport2($model),
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
        $model->tglAwal=$params['tglAwal'];
        $model->tglAkhir=$params['tglAkhir'];
        $model->skpd=$params['skpd'];
               
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayResumeReport2 ($model),
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
        
        $tglAwal = new \DateTime($model->tglAwal);
        $tglAkhir = new \DateTime($model->tglAkhir);
        $col = 'D';
        for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {
            $activeSheet->setCellValue($col.'2',$x->format('d'));
            ++$col;
        }
        $activeSheet->setCellValue($col++.'2','H')->setCellValue($col++.'2','S')
            ->setCellValue($col++.'2','I')->setCellValue($col++.'2','C')
            ->setCellValue($col.'2','TD');
        
        $baseRow=3;
        foreach ($dataProvider->getModels() as $absen) {
            $activeSheet->setCellValue('A'.$baseRow, $baseRow-2)
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
                ->setCellValue($col.$baseRow,$absen['tugas_dinas']);
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
        $model->tglAwal = $params['tglAwal'];
        $model->tglAkhir = $params['tglAkhir'];
        $model->skpd = $params['skpd'];
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->arrayResumeReport2 ($model),
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
    
    public function arrayResumeReport2($model) {
        $deptids = [];
        if ($model->eselon4 != NULL) {
            $deptids=[$model->eselon4];
        }elseif ($model->eselon3 != NULL) {
            $eselon3s = [$model->eselon3];
            $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $model->eselon3)->queryAll();
            $deptids = array_merge($eselon3s, array_column($eselon4s,'DeptID'));          
        }elseif ($model->skpd !=NULL) {
            $skpd = [$model->skpd];
            $eselon3s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $model->skpd)->queryAll();
            if(count($eselon3s)) {
                $deptids = array_merge($skpd, array_column($eselon3s,'DeptID'));
                foreach ($eselon3s as $eselon3 ) {
                    $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $eselon3['DeptID'])->queryAll();
                    $deptids = array_merge($deptids, array_column($eselon4s,'DeptID'));
                }
            } else $deptids = $skpd;
        }
        
        $tglAwal = new \DateTime($model->tglAwal);
        if(in_array($tglAwal->format('w'),[1,2,3,4])) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'senin-kamis'])->one();
        }elseif($tglAwal->format('w') == 5) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'jumat'])->one();
        }
        
        $renAwal = new \DateTime($model->tglAwal);
        if($model->tglAkhir == NULL) {
            $renAkhir = $renAwal;
        }else $renAkhir = new \DateTime($model->tglAkhir);
                
        $query = Userinfo::find()->select('userid, badgenumber, name')->with([
            'keteranganAbsen'=>function($query) use($renAwal, $renAkhir) {
                $query->andWhere('(tgl_awal >= :renAwal and tgl_awal <= :renAkhir) or '
                        . '(tgl_akhir >= :renAwal and tgl_akhir <= :renAkhir)',[':renAwal'=>$renAwal->format('Y-m-d'), ':renAkhir'=>$renAkhir->format('Y-m-d')]);
            },
            'checkinoutsDaily'=>function ($query) use($renAwal, $renAkhir) {
                $query->andWhere('DATE(datang) >= :renAwal and DATE(datang) <= :renAkhir',[':renAwal'=>$renAwal->format('Y-m-d'), ':renAkhir'=>$renAkhir->format('Y-m-d')]);
                $query->orderBy('DATE(datang) ASC');
            }
            ])
        ->where(['IN','defaultdeptid', $deptids])
        ->orderBy('userid ASC')
        ->asArray()->all();        
        
        $user = array();
         
        foreach ($query as $userInfo) {
            $id = $userInfo['userid'];
            $user[$id] = [
                'pin'=> $userInfo['badgenumber'],
                'name'=> $userInfo['name']
            ];
            $hadir = 0;
            if (count($userInfo['checkinoutsDaily'])) {
                foreach ($userInfo['checkinoutsDaily'] as $checkinout) {
                    
                    $tglDatang = new \DateTime($checkinout['datang']);
                    if ($checkinout['pulang'] == NULL) {
                        $tglPulang = $tglDatang;
                    }else $tglPulang = new \DateTime($checkinout['pulang']);
                    
                    $user[$id][$tglDatang->format('Y-m-d')] = 'H';
                    if ( ! KeteranganAbsen::find()
                            ->where('userid =:id AND :tgl BETWEEN tgl_awal AND IF(tgl_akhir IS NULL, tgl_awal, tgl_akhir)',
                                    [':id'=>$id, ':tgl'=>$tglDatang->format('Y-m-d')])
                            ->exists() ) $hadir += 1;
                }
                $user[$id] = array_merge($user[$id], [
                    'hadir'=>$hadir
                ]);
            }
                   
            $sakit = 0; $ijin = 0; $cuti = 0; $tugas_dinas = 0;  
            if (count($userInfo['keteranganAbsen'])) {                              
                foreach ($userInfo['keteranganAbsen'] as $ketAbsen) {                    
                    $tglAwal = new \DateTime($ketAbsen['tgl_awal']);
                    if ($ketAbsen['tgl_akhir'] == NULL) {
                        $tglAkhir = $tglAwal;
                    } else $tglAkhir = new \DateTime($ketAbsen['tgl_akhir']);                                     
                    
                    if ($tglAwal < $renAwal) $tglAwal = $renAwal;
                    if ($tglAkhir > $renAkhir) $tglAkhir =$renAkhir;
                    
                    for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {
                        $user[$id][$x->format('Y-m-d')] = $ketAbsen['statusid'];
                        if ($ketAbsen['statusid'] == 'S') {
                            $sakit += 1;
                        } elseif ($ketAbsen['statusid'] == 'I') {
                            $ijin += 1;
                        } elseif ($ketAbsen['statusid'] == 'C') {
                            $cuti += 1;
                        } elseif ($ketAbsen['statusid'] == 'TD') {
                            $tugas_dinas += 1;
                        }
                    }
                }               
            }
            $user[$id] = array_merge($user[$id],[
                    'sakit'=>$sakit,
                    'ijin'=>$ijin,
                    'cuti'=>$cuti,
                    'tugas_dinas'=>$tugas_dinas,
                ]);
            
            $start = new \DateTime($model->tglAwal);
            $end = new \DateTime($model->tglAkhir);
            for($i= $start ; $i <= $end; $i->modify('+1 day')) {
                if (TglLibur::find()->where(['tgl_libur'=>$i->format('Y-m-d')])->exists() || in_array($i->format('w'),[0,6])) {
                    $user[$id][$i->format('Y-m-d')] = 'L';
                }
            }
            
        }
        return $user;
    }
}
