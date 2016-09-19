<!DOCTYPE html>
<?php
use app\models\Departments;

$dept = Departments::find()->where(['DeptID'=>$model->skpd])->one();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Laporan Resume Absen</title>
        <style type="text/css">
            @page {
                margin-top: 2cm;
                margin-bottom: 1.5cm;
                margin-left: 2cm;
                margin-right: 2cm;
            }
            table{border-spacing: 0;border-collapse: collapse; width: 100%;}
            table td, table th{border: 1px solid #ccc;}
        </style>
    </head>
    <body>
        <div class="page">
            <h2>Laporan Absen Resume</h2>
            <h3><?= 'Unit Kerja : '.$dept->DeptName ?></h3>
            <h4><?= 'Periode : '.$model->tglAwal.' - '.$model->tglAkhir ?></h4>
            <table border="0">
                <tr>
                    <th>No</th>
                    <th>PIN</th>
                    <th>Nama</th>
                    <?php
                    $tglAwal = new \DateTime($model->tglAwal);
                    $tglAkhir = new \DateTime($model->tglAkhir);
                    for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {
                        echo '<th>'.$x->format('d').'</th>';
                    }
                    ?>
                    <th>H</th>
                    <th>S</th>
                    <th>I</th>
                    <th>C</th>
                    <th>TD</th>
                    <th>TK</th>
                </tr>
                <?php
                $no = 1;
                foreach ($dataProvider->getModels() as $absensi) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $absensi['pin'] ?></td>
                    <td><?= $absensi['name'] ?></td>
                    <?php
                    $tglAwal = new \DateTime($model->tglAwal);
                    $tglAkhir = new \DateTime($model->tglAkhir);
                    for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {
                        echo '<td>'.$absensi[$x->format('Y-m-d')].'</td>';
                    }
                    ?>
                    <td><?= $absensi['hadir'] ?></td>
                    <td><?= $absensi['sakit'] ?></td>
                    <td><?= $absensi['ijin'] ?></td> 
                    <td><?= $absensi['cuti'] ?></td>
                    <td><?= $absensi['tugas_dinas'] ?></td>
                    <td><?= $absensi['tampa_keterangan'] ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
        
    </body>
</html>
