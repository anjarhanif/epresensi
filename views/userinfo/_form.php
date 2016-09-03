<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Departments;

/* @var $this yii\web\View */
/* @var $model app\models\Userinfo */
/* @var $form yii\widgets\ActiveForm */

$skpdid = Yii::$app->user->identity->dept_id;
?>

<div class="userinfo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Card')->textInput(['maxlength' => true,'style'=>'width: 600px']) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'style'=>'width: 600px']) ?>

    <?= $form->field($model, 'defaultdeptid')->widget(Select2::className(),[
        'data' => Departments::getDeptidNames($skpdid),
        'options' => ['placeholder'=>'[ Pilih Unit Kerja ]'],
        'pluginOptions'=>['allowClear'=>TRUE, 'width'=>'600px'],
    ]) ?>
    
<!--
    <?= $form->field($model, 'Password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'badgenumber')->textInput(['maxlength' => true,]) ?>

    <?= $form->field($model, 'Privilege')->textInput() ?>

    <?= $form->field($model, 'AccGroup')->textInput() ?>

    <?= $form->field($model, 'TimeZones')->textInput(['maxlength' => true]) ?>
-->
    <?= $form->field($model, 'Gender')->textInput(['maxlength' => true, 'style'=>'width: 600px']) ?>

    <?= $form->field($model, 'Birthday')->textInput(['style'=>'width: 600px']) ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true, 'style'=>'width: 600px']) ?>

    <?= $form->field($model, 'zip')->textInput(['maxlength' => true, 'style'=>'width: 600px']) ?>

    <?= $form->field($model, 'ophone')->textInput(['maxlength' => true, 'style'=>'width: 600px']) ?>

    <?= $form->field($model, 'FPHONE')->textInput(['maxlength' => true, 'style'=>'width: 600px']) ?>
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
