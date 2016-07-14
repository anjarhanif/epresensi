<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CheckinoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Checkinouts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checkinout-monitor">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute'=>'id', 'format'=>'raw', 'contentOptions'=>['style'=>'width:10%']],
            ['attribute' => 'userid', 'format'=>'raw', 'contentOptions'=>['style'=>'width:10%']],
            'checktime',
            'checktype',
            //'verifycode',
            'SN',
            // 'sensorid',
            // 'WorkCode',
            // 'Reserved',

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{view}'],
        ],
    ]); ?>
</div>
