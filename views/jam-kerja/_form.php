<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\JenisJamkerja;

/* @var $this yii\web\View */
/* @var $model app\models\JamKerja */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jam-kerja-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_jenis')->dropDownList(JenisJamkerja::getListJenisJamkerja(),[
        'prompt' => '[Pilih Jenis Jam Kerja]',
        'style' => 'width : 300px',
    ]) ?>

    <?= $form->field($model, 'no_hari')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jam_masuk')->textInput() ?>

    <?= $form->field($model, 'jam_pulang')->textInput() ?>

    <?= $form->field($model, 'mulai_cekin')->textInput() ?>

    <?= $form->field($model, 'akhir_cekout')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
