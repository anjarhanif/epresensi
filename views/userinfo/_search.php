<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\UserinfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userinfo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'badgenumber') ?>

    <?= $form->field($model, 'defaultdeptid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'Password') ?>

    <?php // echo $form->field($model, 'Card') ?>

    <?php // echo $form->field($model, 'Privilege') ?>

    <?php // echo $form->field($model, 'AccGroup') ?>

    <?php // echo $form->field($model, 'TimeZones') ?>

    <?php // echo $form->field($model, 'Gender') ?>

    <?php // echo $form->field($model, 'Birthday') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'zip') ?>

    <?php // echo $form->field($model, 'ophone') ?>

    <?php // echo $form->field($model, 'FPHONE') ?>

    <?php // echo $form->field($model, 'pager') ?>

    <?php // echo $form->field($model, 'minzu') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'SN') ?>

    <?php // echo $form->field($model, 'SSN') ?>

    <?php // echo $form->field($model, 'UTime') ?>

    <?php // echo $form->field($model, 'State') ?>

    <?php // echo $form->field($model, 'City') ?>

    <?php // echo $form->field($model, 'SECURITYFLAGS') ?>

    <?php // echo $form->field($model, 'DelTag') ?>

    <?php // echo $form->field($model, 'RegisterOT') ?>

    <?php // echo $form->field($model, 'AutoSchPlan') ?>

    <?php // echo $form->field($model, 'MinAutoSchInterval') ?>

    <?php // echo $form->field($model, 'Image_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
