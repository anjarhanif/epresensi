<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TglLiburSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tgl Libur';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tgl-libur-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tgl Libur', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute'=>'id', 'contentOptions'=>['style'=>'width : 8%']],
            ['attribute'=>'tgl_libur', 'contentOptions'=>['style'=>'width :20%']],
            'keterangan',

            ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>['style'=>'width :9%']],
        ],
    ]); ?>
</div>
