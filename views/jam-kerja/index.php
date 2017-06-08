<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\JamKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jam Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jam Kerja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style'=>'width : 75%'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            //'id',
            //'id_jenis',
            ['attribute' => 'jenisJamker', 'value'=>'jenisJamkerja.nama_jenis', 'contentOptions' => ['style'=>'width : 30%']],
            'no_hari',
            ['attribute' => 'jam_masuk', 'contentOptions' => ['style'=>'width : 20%']],
            ['attribute' => 'jam_pulang', 'contentOptions' => ['style'=>'width : 20%']],
            // 'mulai_cekin',
            // 'akhir_cekout',

            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['style'=>'width : 8%']],
        ],
    ]); ?>
</div>
