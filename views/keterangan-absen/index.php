<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\KeteranganAbsenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Keterangan Absen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keterangan-absen-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Keterangan Absen', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','contentOptions'=>['style'=>'width :6%']],
            //'id',
            ['attribute'=>'username','value'=>'userinfo.name','contentOptions'=>['style'=>'width :30%']],
            ['attribute'=>'statusid', 'contentOptions'=>['style'=>'width :6%']],
            ['attribute'=>'tgl_awal','contentOptions'=>['style'=>'width :10%']],
            ['attribute'=>'tgl_akhir','contentOptions'=>['style'=>'width :10%']],
            'keterangan',

            ['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width :6%']],
        ],
    ]); ?>
    <?= Html::a('Export Excel', ['export-excel','params'=> Yii::$app->request->queryParams], ['class'=>'btn btn-info']); ?>&nbsp;
    <?= Html::a('Export PDF', ['export-pdf','params'=> Yii::$app->request->queryParams], ['class'=>'btn btn-info']); ?>  
</div>
