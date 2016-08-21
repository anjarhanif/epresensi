<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute'=>'id','contentOptions'=>['style'=>'width: 7%']],
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'email:email',
            ['attribute'=>'dept_id','value'=>'department.DeptName'],
            ['attribute'=>'role_id', 'value'=>'role.role_name'],
            ['attribute'=>'status_id','value'=>'status.status_name'],
            'created_at:datetime',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width: 8%']],
        ],
    ]); ?>
</div>
