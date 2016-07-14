<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\CheckinoutSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="checkinout-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'checktime') ?>

    <?= $form->field($model, 'checktype') ?>

    <?= $form->field($model, 'verifycode') ?>

    <?php // echo $form->field($model, 'SN') ?>

    <?php // echo $form->field($model, 'sensorid') ?>

    <?php // echo $form->field($model, 'WorkCode') ?>

    <?php // echo $form->field($model, 'Reserved') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
