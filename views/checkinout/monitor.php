<!DOCTYPE html>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CheckinoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Monitoring';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checkinout-monitor">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(['timeout'=>false, 'id'=>'gridview']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //['attribute'=>'userid', 'format'=>'raw', 'contentOptions'=>['style'=>'width:8%']],
            [
                'attribute'=>'pin', 
                'value'=> function ($model) { return (int) $model->userinfo->badgenumber; }, 
                'contentOptions'=>['style'=>'width:8%']
            ],
            ['attribute' => 'name','value'=>'userinfo.name', 'format'=>'raw', 'contentOptions'=>['style'=>'width:40%']],
            'checktime',
            //'checktype',
            //'verifycode',
            ['attribute'=>'SN','contentOptions'=>['style'=>'width:10%']],
            ['attribute'=>'alias','value'=>'device.Alias'],
            // 'sensorid',
            // 'WorkCode',
            // 'Reserved',
        ],
    ]); ?>
    <?php Pjax::end() ?>
    <?php $this->registerJs('
                var currentData="";
                var check = function() {
                    setTimeout( function() {
                        $.ajax({ url: "'.Url::to(['checkinout/check']).'",
                        success: function(data) {
                            if(currentData != data.lastId) {
                                currentData = data.lastId;                           
                                $.pjax({
                                    url:"'.Url::to(['checkinout/monitor']).'",
                                    container:"#gridview",
                                    timeout:false,
                                    replace:false,
                                }).done(function(data) {
                                    check();
                                });
                            }
                            else {
                                check();
                            }
                        }, dataType: "json"});
                    }, 5000);
                }
                check();
            ');
        ?>
</div>
