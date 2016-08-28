<?php
use yii\helpers\Html;
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'Si-Sensi';
?>
<div class="site-index">
    
    <h1 align="center">Si-SenSi Terintegrasi <i class="fa fa-calendar-check-o"></i></h1>

    <p align="center" class="lead">Sistem Informasi Presensi Elektronik Terintegrasi</p>
    <hr align="center">
    
    <div class="body-content">
        
        <div class="row">
            <div class="col-lg-6">
                <?= Highcharts::widget([
                    'options' => [
                        'chart' => ['type'=> 'column'],
                        'title'=> ['text'=> 'Tingkat Kehadiran Pegawai (%)'],
                        'xAxis'=> [
                            'categories'=> ['Hari Ini : '.date("Y-m-d")]
                        ],
                        'yAxis'=> [
                            'title'=> ['text'=> 'Persen Kehadiran(%)']
                        ],
                        'series'=> $series,
                        'plotOptions'=> [
                            'column'=> [
                                'dataLabels'=> ['enabled'=> TRUE]
                            ]
                        ]
                    ]
                ])
                ?>
            </div>
            <div class="col-lg-6">
            <?= GridView::widget([
                'dataProvider'=> $dataProvider,
                'formatter'=>['class'=>'yii\i18n\Formatter' ,'nullDisplay'=>'Nihil'],
                //'options'=>['style'=>'width : 50%'],
                'columns'=> [
                    ['class'=>'yii\grid\SerialColumn','contentOptions'=>['style'=>'width :7%']],
                    'skpd',
                    ['attribute'=>'jmlpeg', 'contentOptions'=>['style'=>'width: 10%']],
                    ['attribute'=>'jmlhadir', 'contentOptions'=>['style'=>'width: 10%']],
                    ['attribute'=>'%hadir', 'contentOptions'=>['style'=>'width: 10%']],
                ]
            ])
            ?>    
            </div>          
        </div> 
    </div>
</div>
