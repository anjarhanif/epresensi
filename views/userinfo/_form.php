<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Userinfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userinfo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'badgenumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'defaultdeptid')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<!--
    <?= $form->field($model, 'Password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Card')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Privilege')->textInput() ?>

    <?= $form->field($model, 'AccGroup')->textInput() ?>

    <?= $form->field($model, 'TimeZones')->textInput(['maxlength' => true]) ?>
-->
    <?= $form->field($model, 'Gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Birthday')->textInput() ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ophone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'FPHONE')->textInput(['maxlength' => true]) ?>
<!--
    <?= $form->field($model, 'pager')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'minzu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SSN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UTime')->textInput() ?>

    <?= $form->field($model, 'State')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'City')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SECURITYFLAGS')->textInput() ?>

    <?= $form->field($model, 'DelTag')->textInput() ?>

    <?= $form->field($model, 'RegisterOT')->textInput() ?>

    <?= $form->field($model, 'AutoSchPlan')->textInput() ?>

    <?= $form->field($model, 'MinAutoSchInterval')->textInput() ?>

    <?= $form->field($model, 'Image_id')->textInput() ?>
-->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
