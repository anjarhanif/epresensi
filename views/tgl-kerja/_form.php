<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\JenisJamkerja;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TglKerja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tgl-kerja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_jenis')->dropDownList(JenisJamkerja::getListJenisJamkerja(), [
        'prompt' => '[Pilih Jenis Jam Kerja]',
        'style' => 'width : 300px',
    ]) ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_awal')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=>['changeYear'=>TRUE],
        'options'=>['class'=>'form-control', 'style'=>'width : 500px']
    ]) ?>

    <?= $form->field($model, 'tgl_akhir')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=>['changeYear'=>TRUE],
        'options'=>['class'=>'form-control', 'style'=>'width : 500px']
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
