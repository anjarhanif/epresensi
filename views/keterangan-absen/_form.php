<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use app\models\PermissionHelpers;
use app\models\StatusAbsen;
use app\models\Userinfo;
use app\models\Departments;

if (PermissionHelpers::requireMinimumRole('AdminSKPD')) {
    $deptid = Yii::$app->user->identity->dept_id;
    $deptids = Departments::getDeptids($deptid);
} else $deptids=NULL;

/* @var $this yii\web\View */
/* @var $model app\models\KeteranganAbsen */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="keterangan-absen-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userid')->widget(Select2::className(),[
        'data' => Userinfo::getUserinfoList($deptids),
        'options' => ['placeholder'=>'[ Pilih Pegawai ]'],
        'pluginOptions'=>['allowClear'=>TRUE, 'width'=>'500px'],
    ])
    ?>

    <?= $form->field($model, 'statusid')->dropDownList(StatusAbsen::statusAbsenList(), [
        'prompt'=>'[ Pilih Status ]',
        'style'=>'width: 300px',
    ]) ?>

    <?= $form->field($model, 'tgl_awal')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=> ['changeYear'=>TRUE],
        'options'=>['class'=>'form-control','style'=>'width:200px']
    ]) ?>

    <?= $form->field($model, 'tgl_akhir')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=> ['changeYear'=>TRUE],
        'options'=>['class'=>'form-control','style'=>'width:200px']
    ]) ?>

    <?= $form->field($model, 'keterangan')->textarea(['rows'=>2]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
