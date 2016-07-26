<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\depdrop;

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
    'id' => 'skpd-id',
]) ?>
<?= $form->field($model,'eselon3')->widget(depdrop\DepDrop::className(), [
    'options' => ['id' => 'eselon3'],
    'pluginOptions' => [
        'depends' => ['skpd-id'],
        'placeholder' => 'Pilih Eselon 3',
        'url' => Url::to(['/report/eselon3-list'])
    ]
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