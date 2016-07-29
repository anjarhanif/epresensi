<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Laporan Harian</title>
        <style type="text/css">
            .page{padding: 2cm;}
            table{border-spacing: 0;border-collapse: collapse; width: 100%;}
            table td, table th{border: 1px solid #ccc;}
        </style>
    </head>
    <body>
        <div class="page">
            <h1>Laporan Absen Harian</h1>
            <table border="0">
                <tr>
                    <th>No</th>
                    <th>UserID</th>
                    <th>Nama</th>
                    <th>Datang</th>
                    <th>Pulang</th>
                </tr>
                <?php
                $no = 1;
                foreach ($dataProvider->getModels() as $absensi) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $absensi['userid'] ?></td>
                    <td><?= $absensi['name'] ?></td>
                    <td><?= $absensi['Datang'] ?></td>
                    <td><?= $absensi['Pulang'] ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
        
    </body>
</html>
