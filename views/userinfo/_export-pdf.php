<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>User Info</title>
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
            <h2>User Info</h2>
            <h3>Satuan Kerja : <?= $dept->DeptName; ?></h3>
            <table border="0">
                <tr>
                    <th>No</th>
                    <th>PIN</th>
                    <th>Nama</th>
                    <th>Unit Kerja</th>
                </tr>
                <?php
                $no = 1;
                foreach ($dataProvider->getModels() as $userinfo) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= (int)$userinfo->badgenumber ?></td>
                    <td><?= $userinfo->name ?></td>
                    <td><?= $userinfo->department->DeptName ?></td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
        
    </body>
</html>
