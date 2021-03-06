<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Userinfo';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="userinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Userinfo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //['attribute'=>'userid', 'contentOptions'=>['style'=>'width: 8%']],
            [
                'attribute'=>'badgenumber',
                'value'=> function($data) {return (int)$data->badgenumber;},
                'contentOptions'=>['style'=>'width: 8%']
            ],
            'name',
            ['attribute'=>'Card', 'contentOptions'=>['style'=>'width: 12%']],
            ['attribute'=>'deptname', 'value'=>'department.DeptName'],
            //'Password',
            // 'Privilege',
            // 'AccGroup',
            // 'TimeZones',
            // 'Gender',
            // 'Birthday',
            // 'street',
            // 'zip',
            // 'ophone',
            // 'FPHONE',
            // 'pager',
            // 'minzu',
            // 'title',
            // 'SN',
            // 'SSN',
            // 'UTime',
            // 'State',
            // 'City',
            // 'SECURITYFLAGS',
            // 'DelTag',
            // 'RegisterOT',
            // 'AutoSchPlan',
            // 'MinAutoSchInterval',
            // 'Image_id',

            ['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width: 6%']],
        ],
    ]); ?>
    <?= Html::a('Export Excel', ['export-excel','params'=> Yii::$app->request->queryParams], ['class'=>'btn btn-info']); ?>&nbsp;
    <?= Html::a('Export PDF', ['export-pdf','params'=> Yii::$app->request->queryParams], ['class'=>'btn btn-info']); ?>
</div>
