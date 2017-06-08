<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TglKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tgl Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tgl-kerja-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tgl Kerja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'width : 90%'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute'=>'id', 'contentOptions'=>['style'=>'width : 8%']],
            //'id_jenis',
            ['attribute' => 'JenisJamker', 'value'=>'jenisJamkerja.nama_jenis'],
            'label',
            ['attribute'=>'tgl_awal', 'contentOptions'=>['style'=>'width:13%']],
            ['attribute'=>'tgl_akhir', 'contentOptions'=>['style'=>'width:13%']],

            ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width:8%']],
        ],
    ]); ?>
</div>
