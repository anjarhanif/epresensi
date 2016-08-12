<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\jui\DatePicker;

use app\models\Departments;

$this->title = 'Laporan Harian';
$this->params['breadcrumbs'][]=['label'=>'Laporan Kehadiran', 'url'=>['index']];
$this->params['breadcrumbs'][]= $this->title;

?>
<h1>Laporan Harian</h1>
<?php $form = ActiveForm::begin([
    'action' => ['day-report'],
    'method' => 'get',
]); ?>
<div class="row">
    <div class="col-lg-6">
    
    <?= $form->field($model,'tglAwal')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'options'=>['class'=>'form-control' ,'style'=>'width : 500px'],
        'clientOptions'=>[            
            'changeYear'=>TRUE
        ]
    ]) ?>
    <?= $form->field($model,'skpd')->dropDownList(Departments::deptList(), [
        'prompt' => '[ Pilih SKPD ]',
        'style' => 'width:500px',
        'id' => 'skpd-id',
    ]) ?>
        
    </div>
    <div class="col-lg-6">
    <?= $form->field($model,'eselon3')->widget(DepDrop::className(), [
        'options' => ['id' => 'eselon3-id', 'style'=>'width: 500px'],
        'pluginOptions' => [
            'depends' => ['skpd-id'],
            'placeholder' => 'Pilih Eselon 3',
            'url' => Url::to(['/report/eselon3-list'])
        ]
    ]) ?> 
    <?= $form->field($model,'eselon4')->widget(DepDrop::className(), [
        'options'=>['style'=>'width : 500px'],
        'pluginOptions' => [
            'depends' => ['skpd-id', 'eselon3-id'],
            'placeholder' => 'Pilih Eselon 4',
            'url' => Url::to(['/report/eselon4-list'])
        ],
    ]); ?>
    </div>
</div>

<?= Html::submitButton('Tampilkan',['class'=>'btn btn-primary']) ?>
<p></p>
<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'formatter'=>['class'=>'yii\i18n\Formatter' ,'nullDisplay'=>'Nihil'],
    'pager'=>[
        'firstPageLabel'=>'First',
        'lastPageLabel'=>'Last',
        'maxButtonCount'=>5
    ],
    'columns'=>[
        ['class'=>'yii\grid\SerialColumn'],
        'userid',
        'name',
        'datang',
        'pulang',
        'Keterangan',
    ]
]); ?>

<?= Html::a('Export Excel', ['repday-excel', 'params'=>$model], ['class'=>'btn btn-info']); ?>&nbsp;
<?= Html::a('Export PDF', ['repday-pdf', 'params'=>$model], ['class'=>'btn btn-info']); ?>  

<p></p>

