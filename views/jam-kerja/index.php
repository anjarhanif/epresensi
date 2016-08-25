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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','contentOptions'=>['style'=>'width :5%']],

            ['attribute'=>'id','contentOptions'=>['style'=>'width : 7%']],
            'nama_jamker',
            ['attribute'=>'jam_masuk', 'contentOptions'=>['style'=>'width : 10%']],
            ['attribute'=>'jam_pulang', 'contentOptions'=>['style'=>'width : 10%']],
            ['attribute'=>'mulai_cekin','contentOptions'=>['style'=>'width : 10%']],
            ['attribute'=>'akhir_cekout','contentOptions'=>['style'=>'width : 10%']],

            ['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width :7%']],
        ],
    ]); ?>
</div>
