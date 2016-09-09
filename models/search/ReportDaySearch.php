<?php

namespace app\models\search;

use yii\db\Query;
use yii\data\ActiveDataProvider;
use app\models\ReportForm;
use app\models\JamKerja;

/**
 * Description of ReportDaySearch
 *
 * @author yasrul
 */
class ReportDaySearch extends ReportForm {
    //put your code here
    //public $pin;
    //public $name;
    //public $datang;
    //public $pulang;
    //public $keterangan;
    
    public function rules() {
        return [
            [['skpd','eselon3','eselon4','badgenumber','name','keterangan'],'string'],
            [['tglAwal','tglAkhir','datang','pulang'],'date'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'badgenumber'=>'PIN',
            'name'=>'Nama',
            'datang'=>'Datang',
            'pulang'=>'Pulang',
            'keterangan'=>'Keterangan',
        ];
    }
    
    public function search($params) {
        
        $this->load($params);
        
        $deptids = [];
        if ($this->eselon4 != NULL) {
            $deptids=[$this->eselon4];
        }elseif ($this->eselon3 != NULL) {
            $eselon3s = [$this->eselon3];
            $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $this->eselon3)->queryAll();
            $deptids = array_merge($eselon3s, array_column($eselon4s,'DeptID'));          
        }elseif ($this->skpd !=NULL) {
            $skpd = [$this->skpd];
            $eselon3s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $this->skpd)->queryAll();
            if(count($eselon3s)) {
                $deptids = array_merge($skpd, array_column($eselon3s,'DeptID'));
                foreach ($eselon3s as $eselon3 ) {
                    $eselon4s = Yii::$app->db->createCommand('select DeptID from departments where supdeptid =:deptid')
                    ->bindValue(':deptid', $eselon3['DeptID'])->queryAll();
                    $deptids = array_merge($deptids, array_column($eselon4s,'DeptID'));
                }
            } else $deptids = $skpd;
        }
               
        $tglAwal = new \DateTime($this->tglAwal);
        if(in_array($tglAwal->format('w'),[1,2,3,4])) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'senin-kamis'])->one();
        }elseif($tglAwal->format('w') == 5) {
            $jamKerja = JamKerja::find()->where(['nama_jamker'=>'jumat'])->one();
        }

       $query = (new Query())->select(['u.badgenumber','u.name','IF(COUNT(c.checktime) > 0, MIN(c.checktime),"Nihil") AS datang', 
                'IF(COUNT(c.checktime) > 1, MAX(c.checktime),"Nihil" ) AS pulang', 
                'IF(k.statusid IS NULL, IF(:tgl <> CURDATE(), IF(TIME(MIN(c.checktime)) > :jamMasuk OR TIME(MAX(c.checktime)) < :jamPulang, "TH/CP",IF(COUNT(c.checktime) = 0,"TK","")),""), k.statusid) AS keterangan'])
               ->from('userinfo u')
               ->leftJoin('checkinout c','u.userid=c.userid AND DATE(c.checktime)=:tgl')
               ->leftJoin('keterangan_absen k','u.userid=k.userid AND :tgl BETWEEN k.tgl_awal AND (IF(k.tgl_akhir IS NULL, k.tgl_awal, k.tgl_akhir)) ')
               ->where(['IN','u.defaultdeptid',$deptids])
               ->groupBy('u.userid, DATE(c.checktime)')
               ->orderBy('u.userid ASC')
               ->addParams([':tgl'=>$tglAwal,':jamMasuk'=>$jamKerja->jam_masuk,':jamPulang'=>$jamKerja->jam_pulang])
               ->all();
       
       $dataProvider = new \yii\data\ArrayDataProvider([
           'allModels' => $query,
           
       ]);
       
       return $dataProvider;
    }
}
