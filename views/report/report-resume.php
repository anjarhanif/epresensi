<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\jui\DatePicker;

use app\models\Departments;

$this->title = 'Laporan Resume';
$this->params['breadcrumbs'][]=['label'=>'Laporan Kehadiran', 'url'=>['index']];
$this->params['breadcrumbs'][]= $this->title;
?>
<h1>Laporan Resume</h1>
<?php $form = ActiveForm::begin([
    'action' => ['resume-report'],
    'method' => 'get',
]); ?>
<div class="row">
    <div class="col-lg-6">
    <?= $form->field($model,'tglAwal')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=>['changeYear'=>TRUE],
        'options'=>['class'=>'form-control', 'style'=>'width: 500px']
    ]);
    ?>
    <?= $form->field($model,'tglAkhir')->widget(DatePicker::className(), [
        'dateFormat'=>'yyyy-MM-dd',
        'clientOptions'=>['changeYear'=>TRUE],
        'options'=>['class'=>'form-control', 'style'=>'width: 500px']
    ])   
    ?>      
    </div>
    <div class="col-lg-6">
    
    <?= $form->field($model,'skpd')->dropDownList(Departments::deptList(1), [
        'prompt' => '[ Pilih SKPD ]',
        'style' => 'width:500px',
        'id' => 'skpd-id',
    ]) ?>
    <?= $form->field($model,'eselon3')->widget(DepDrop::className(), [
        'options' => ['id' => 'eselon3-id', 'style'=>'width: 500px'],
        'pluginOptions' => [
            'depends' => ['skpd-id'],
            'initialize'=>true,
            'placeholder' => 'Pilih Eselon 3',
            'url' => Url::to(['/report/eselon3-list'])
        ]
    ]) ?> 
    <?= $form->field($model,'eselon4')->widget(DepDrop::className(), [
        'options'=>['style'=>'width : 500px'],
        'pluginOptions' => [
            'depends' => ['skpd-id', 'eselon3-id'],
            'initialize'=>true,
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
        ['class'=>'yii\grid\SerialColumn','contentOptions'=>['style'=>'width :5%']],
        ['attribute'=>'userid','contentOptions'=>['style'=>'width :7%']],
        'name',
        ['attribute'=>'sakit','contentOptions'=>['style'=>'width :7%']],
        ['attribute'=>'ijin','contentOptions'=>['style'=>'width :7%']],
        ['attribute'=>'tugas-dinas','label'=>'TD','contentOptions'=>['style'=>'width :7%']],
        ['attribute'=>'cuti','contentOptions'=>['style'=>'width :7%']],
        ['attribute'=>'th-cp','label'=>'TH/CP','contentOptions'=>['style'=>'width :7%']],
        ['attribute'=>'alpa','contentOptions'=>['style'=>'width :7%']],
    ]
]); ?>

<?= Html::a('Export Excel', ['represume-excel', 'params'=>$model], ['class'=>'btn btn-info']); ?>&nbsp;
<?= Html::a('Export PDF', ['represume-pdf', 'params'=>$model], ['class'=>'btn btn-info']); ?>  

<p></p>


