<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

use app\models\Departments;

?>
<h1>Laporan Harian</h1>

<?php $form = ActiveForm::begin([
    'action' => ['day-report'],
    'method' => 'get',
]); ?>
<?= $form->field($model,'skpd')->dropDownList(Departments::deptList(), [
    'prompt' => '[ Pilih SKPD ]',
    'style' => 'width:500px',
    'onchange' => '$.post("'.Yii::$app->urlManager->createUrl('report/dept-options?id=').'"+$(this).val(), '.
    'function (data) {'.
        '$("select#eselon3").html(data);'.
    '});'
]) ?>
<?= $form->field($model,'eselon3')->dropDownList(Departments::deptList(), [
    'prompt' => '[ Pilih Eselon 3]',
    'style' => 'width:500px',
    'onchange' => '$.post("'.Yii::$app->urlManager->createUrl('report/dept-options?id=').'"+$(this).val(), '.
    'function (data) {'.
        '$("select#eselon4").html(data);'.
    '});',
    'id' => 'eselon3',
]) ?>
<?= $form->field($model,'eselon4')->dropDownList(Departments::deptList(), [
    'prompt' => '[ Pilih Eselon 4 ]',
    'style' => 'width:500px',
    'id' => 'eselon4',
]) ?>
<?= $form->field($model,'tgl') ?>
<?= Html::submitButton('Tampilkan',['class'=>'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'yii\grid\SerialColumn'],
        'userid',
        'name',
        'Datang',
        'Pulang',
        
    ]
]);