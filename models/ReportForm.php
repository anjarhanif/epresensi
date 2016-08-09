<?php

namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Description of filtReport
 *
 * @author yasrul
 */
class ReportForm extends Model {
    //put your code here
    public $skpd;
    public $eselon3;
    public $eselon4;
    public $tglAwal;
    public $tglAkhir;


    public function rules() {
        return [
            [['skpd','tglAwal'],'required'],
            [['tglAkhir','eselon3','eselon4'],'safe']
        ];
    }
    
    public function attributeLabels() {
        return [
            'skpd'=>'SKPD',
            'eselon3'=>'Struktur Eselon III',
            'eselon4'=>'Struktur Eselon IV',
            'tglAwal'=>'Tanggal Awal',
            'tglAkhir'=>'Tanggal Akhir'
        ];
    }
}
