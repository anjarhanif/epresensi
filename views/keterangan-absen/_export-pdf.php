<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Keterangan Absen</title>
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
            <h2>Keterangan Absen</h2>
            <h3>Satuan Kerja : <?= $dept->DeptName; ?></h3>
            <table border="0">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Tgl-Awal</th>
                    <th>Tgl-Akhir</th>
                    <th>Keterangan</th>
                </tr>
                <?php
                $no = 1;
                foreach ($dataProvider->getModels() as $keterangan) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $keterangan->userinfo->name ?></td>
                    <td><?= $keterangan->statusid ?></td>
                    <td><?= $keterangan->tgl_awal ?></td>
                    <td><?= $keterangan->tgl_akhir ?></td>
                    <td><?= $keterangan->keterangan ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
        
    </body>
</html>
