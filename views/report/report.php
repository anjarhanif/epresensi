<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;

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

<?= $form->field($model,'eselon3')->widget(DepDrop::className(), [
    'options' => ['id' => 'eselon3-id'],
    'pluginOptions' => [
        'depends' => ['skpd-id'],
        'placeholder' => 'Pilih Eselon 3',
        'url' => Url::to(['/report/eselon3-list'])
    ]
]) ?>
<?= $form->field($model,'eselon4')->widget(DepDrop::className(), [
    'pluginOptions' => [
        'depends' => ['skpd-id', 'eselon3-id'],
        'placeholder' => 'Pilih Eselon 4',
        'url' => Url::to(['/report/eselon4-list'])
    ],
   
]); ?>
<?= $form->field($model,'tgl') ?>
<?= Html::submitButton('Tampilkan',['class'=>'btn btn-primary']) ?>
&nbsp;

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
]); ?>

<?= Html::a('Export Excel', ['export-excel', 'params'=>$model], ['class'=>'btn btn-info']); ?>&nbsp;
<?= Html::a('Export PDF', ['export-pdf', 'params'=>$model], ['class'=>'btn btn-info']); ?>