<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Checkinout */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="checkinout-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'checktime')->textInput() ?>

    <?= $form->field($model, 'checktype')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'verifycode')->textInput() ?>

    <?= $form->field($model, 'SN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sensorid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'WorkCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Reserved')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
