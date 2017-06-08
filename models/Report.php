<?php

namespace app\models;

use Yii;
use yii\db\Query;
use app\models\JamKerja;
use app\models\TglLibur;
use app\models\Userinfo;
use app\models\KeteranganAbsen;
use app\models\TglKerja;

/**
 * Description of Report
 *
 * @author yasrul
 */
class Report {
    //put your code here
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
        $JenisJamker = TglKerja::find()->select('id_jenis')->where(['<=','tgl_awal',$tglAwal->format('Y-m-d')])
                ->andWhere(['>=','tgl_akhir', $tglAwal->format('Y-m-d')])->one();
        if (! $JenisJamker) $JenisJamker = 1;
        $jamKerja = JamKerja::find()->where(['id_jenis'=>$JenisJamker])
                ->andWhere('LOCATE( :noHari, no_hari) > 0',[':noHari'=>$tglAwal->format('w')])->one();
        
        /*
        if(in_array($tglAwal->format('w'),[1,2,3,4])) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'senin-kamis'])->one();
        }elseif($tglAwal->format('w') == 5) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'jumat'])->one();
        }*/
        

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
        
        /*
        $tglAwal = new \DateTime($model->tglAwal);
        if(in_array($tglAwal->format('w'),[1,2,3,4])) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'senin-kamis'])->one();
        }elseif($tglAwal->format('w') == 5) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'jumat'])->one();
        }
        */
        
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
        
        $user = [];
         
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
                    
                    if ( ! (KeteranganAbsen::find()
                            ->where('userid =:id AND :tgl BETWEEN tgl_awal AND IF(tgl_akhir IS NULL, tgl_awal, tgl_akhir)',
                                    [':id'=>$id, ':tgl'=>$tglDatang->format('Y-m-d')])
                            ->exists() || TglLibur::find()->where(['tgl_libur'=>$tglDatang->format('Y-m-d')])->exists() 
                            || in_array($tglDatang->format('w'),[0,6]) ) ) {
                        
                        $user[$id][$tglDatang->format('Y-m-d')] = 'H';
                        $hadir += 1;
                    }
                }
            }
            $user[$id] = array_merge($user[$id], [
                    'hadir'=>$hadir
                ]);
                             
            $renAwal = new \DateTime($model->tglAwal);
            if($model->tglAkhir == NULL) {
                $renAkhir = $renAwal;
            }else $renAkhir = new \DateTime($model->tglAkhir);
            
            $sakit = 0; $ijin = 0; $cuti = 0; $tugas_dinas = 0;
        
            if (count($userInfo['keteranganAbsen'])) {                              
                foreach ($userInfo['keteranganAbsen'] as $ketAbsen) {                    
                    $tglAwal = new \DateTime($ketAbsen['tgl_awal']);
                    if ($ketAbsen['tgl_akhir'] == NULL) {
                        $tglAkhir = $tglAwal;
                    } else $tglAkhir = new \DateTime($ketAbsen['tgl_akhir']);                                     
                    
                    if ($tglAwal < $renAwal) {$tglAwal = $renAwal;}
                    if ($tglAkhir > $renAkhir) {$tglAkhir = $renAkhir;}
                    
                    for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {
                        
                        if (!(TglLibur::find()->where(['tgl_libur'=>$x->format('Y-m-d')])->exists() || in_array($x->format('w'),[0,6]))) {
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
            }
                      
            $start = new \DateTime($model->tglAwal);
            $end = new \DateTime($model->tglAkhir);
            $tk = 0;
            for($i= $start ; $i <= $end; $i->modify('+1 day')) {
                if (TglLibur::find()->where(['tgl_libur'=>$i->format('Y-m-d')])->exists() || in_array($i->format('w'),[0,6])) {
                    $user[$id][$i->format('Y-m-d')] = 'L';
                } elseif (!isset ($user[$id][$i->format('Y-m-d')])) {
                    $user[$id][$i->format('Y-m-d')] = 'TK';
                    $tk += 1;
                }
            }
            $user[$id] = array_merge($user[$id],[
                    'sakit'=>$sakit,
                    'ijin'=>$ijin,
                    'cuti'=>$cuti,
                    'tugas_dinas'=>$tugas_dinas,
                    'tampa_keterangan'=>$tk,
                ]);
            
        }
        return $user;
    }
}
