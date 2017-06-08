<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\JenisJamkerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Jam Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-jamkerja-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jenis Jamkerja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'width : 75%'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'id', 'contentOptions' => ['style' => 'width : 10%']],
            'nama_jenis',
            'keterangan',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
