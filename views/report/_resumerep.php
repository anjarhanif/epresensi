<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Laporan Resume Absen</title>
        <style type="text/css">
            @page {
                margin-top: 2.5cm;
                margin-bottom: 2cm;
                margin-left: 2.5cm;
                margin-right: 2cm;
            }
            table{border-spacing: 0;border-collapse: collapse; width: 100%;}
            table td, table th{border: 1px solid #ccc;}
        </style>
    </head>
    <body>
        <div class="page">
            <h1>Laporan Absen Resume</h1>
            <table border="0">
                <tr>
                    <th>No</th>
                    <th>PIN</th>
                    <th>Nama</th>
                    <th>Sakit</th>
                    <th>Ijin</th>
                    <th>TugasDinas</th>
                    <th>Cuti</th>
                    <th>TH-CP</th>
                    <th>Alpa</th>
                </tr>
                <?php
                $no = 1;
                foreach ($dataProvider->getModels() as $absensi) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $absensi['userid'] ?></td>
                    <td><?= $absensi['name'] ?></td>
                    <td><?= $absensi['sakit'] ?></td>
                    <td><?= $absensi['ijin'] ?></td>
                    <td><?= $absensi['tugas-dinas'] ?></td>
                    <td><?= $absensi['cuti'] ?></td>
                    <td><?= $absensi['th-cp'] ?></td>
                    <td><?= $absensi['alpa'] ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
        
    </body>
</html>
