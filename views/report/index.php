<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Laporan';
?>

<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Laporan Harian</h2>

                <p>Menampilkan daftar hadir harian Pegawai Pemprov NTB per SKPD</p>

                <p><?= Html::a('Laporan Harian', ['report/day-report', 'params'=>$model], ['class'=>'btn btn-info']); ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Laporan Resume</h2>

                <p>Menampilkan daftar resume kehadiran pegawai Pemprov NTB per SKPD</p>

                <p><?= Html::a('Resume Kehadiran', ['report/resume-report', 'params'=>$model], ['class'=>'btn btn-info']); ?></p>
            </div>

        </div>

    </div>
</div>
