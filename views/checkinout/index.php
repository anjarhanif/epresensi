<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CheckinoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Checkinouts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checkinout-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Checkinout', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute'=>'userid', 'format'=>'raw', 'contentOptions'=>['style'=>'width:10%']],
            ['attribute' => 'name','value'=>'user.name', 'format'=>'raw', 'contentOptions'=>['style'=>'width:20%']],
            'checktime',
            'checktype',
            //'verifycode',
            ['attribute'=>'SN','contentOptions'=>['style'=>'width:10%']],
            ['attribute'=>'alias','value'=>'device.Alias'],
            // 'sensorid',
            // 'WorkCode',
            // 'Reserved',

            ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width:7%']],
        ],
    ]); ?>
</div>
