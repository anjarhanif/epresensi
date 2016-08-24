<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Userinfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Userinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userinfo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->userid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->userid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'userid',
            'badgenumber',
            'defaultdeptid',
            'name',
            //'Password',
            //'Card',
            //'Privilege',
            //'AccGroup',
            //'TimeZones',
            'Gender',
            'Birthday',
            'street',
            'zip',
            'ophone',
            'FPHONE',
            //'pager',
            //'minzu',
            'title',
            //'SN',
            //'SSN',
            //'UTime',
            //'State',
            //'City',
            //'SECURITYFLAGS',
            //'DelTag',
            //'RegisterOT',
            //'AutoSchPlan',
            //'MinAutoSchInterval',
            //'Image_id',
        ],
    ]) ?>

</div>
