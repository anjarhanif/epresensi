<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

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
            [
            'attribute'=>'pin',
            'label'=>'PIN',
            'value'=>function ($model) {return (int)$model->userinfo->badgenumber;},
            'contentOptions'=>['style'=>'width :8%']
            ],
            ['attribute'=>'username','value'=>'userinfo.name','contentOptions'=>['style'=>'width :30%']],
            ['attribute'=>'statusid', 'contentOptions'=>['style'=>'width :6%']],
            [
                'attribute'=>'tgl_awal',
                'value'=>'tgl_awal',
                'filter'=>  DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'tgl_awal',
                    'dateFormat'=>'yyyy-MM-dd',
                    'options'=>['class'=>'form-control','style'=>'width:100px']
                ]),
                'contentOptions'=>['style'=>'width :10%']
            ],
            [
                'attribute'=>'tgl_akhir',
                'value'=>'tgl_akhir',
                'contentOptions'=>['style'=>'width :10%'],
                'filter'=>  DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'tgl_akhir',
                    'dateFormat'=>'yyyy-MM-dd',
                    'options'=>['class'=>'form-control','style'=>'width:100px']
                ]),
            ],
            'keterangan',

            ['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width :6%']],
        ],
    ]); ?>
    <?= Html::a('Export Excel', ['export-excel','params'=> Yii::$app->request->queryParams], ['class'=>'btn btn-info']); ?>&nbsp;
    <?= Html::a('Export PDF', ['export-pdf','params'=> Yii::$app->request->queryParams], ['class'=>'btn btn-info']); ?>  
</div>
