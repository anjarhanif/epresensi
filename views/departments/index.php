<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DepartmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unit Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="departments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Unit Kerja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','contentOptions'=>['style'=>'width :5%']],

            ['attribute'=>'DeptID', 'contentOptions'=>['style'=>'width :8%']],
            'DeptName',
            ['attribute'=>'sup_dept','value'=>'supdept.DeptName', 'contentOptions'=>['style'=>'width :30%']],

            ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width :7%']],
        ],
    ]); ?>
</div>
