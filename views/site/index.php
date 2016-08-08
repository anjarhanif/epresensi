<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'e-Presensi';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>e-Presensi Terintegrasi</h1>

        <p class="lead">Sistem Informasi Presensi Elektronik Terintegrasi</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Laporan Harian</h2>

                <p>Menampilkan daftar hadir harian Pegawai Pemprov NTB per SKPD</p>

                <p><?= Html::a('Laporan Harian', ['report/day-report', 'params'=>$model], ['class'=>'btn btn-info']); ?> &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Laporan Resume</h2>

                <p>Menampilkan daftar resume kehadiran pegawai Pemprov NTB per SKPD</p>

                <p><?= Html::a('Resume Kehadiran', ['report/resume-report', 'params'=>$model], ['class'=>'btn btn-info']); ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
