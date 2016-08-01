<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TglLibur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tgl-libur-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tgl_awal')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=> ['changeYear'=>TRUE]
    ]) ?>

    <?= $form->field($model, 'tgl_akhir')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=> ['changeYear'=>TRUE]
    ]) ?>

    <?= $form->field($model, 'keterangan')->textarea(['rows'=>2]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
