<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\jui\DatePicker;
//use kartik\date\DatePicker;
use app\models\Departments;
use app\models\PermissionHelpers;


$this->title = 'Laporan Harian';
$this->params['breadcrumbs'][]=['label'=>'Laporan Kehadiran', 'url'=>['index']];
$this->params['breadcrumbs'][]= $this->title;

if (PermissionHelpers::requireMinimumRole('AdminSKPD')) {
    $deptid = Yii::$app->user->identity->dept_id;
} else $deptid=NULL;

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
    
    <?= $form->field($model,'skpd')->dropDownList(Departments::deptList(1,$deptid), [
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
            'initialize'=>TRUE,
            'placeholder' => 'Pilih Eselon 3',
            'url' => Url::to(['/report/eselon3-list']),
            'params' => ['input-eselon3'],
            
        ]]) 
    ?> 
    <?= $form->field($model,'eselon4')->widget(DepDrop::className(), [
        'options'=>['id'=>'eselon4-id','style'=>'width : 500px'],
        'pluginOptions' => [
            'depends' => ['eselon3-id'],
            'initialize'=>TRUE,
            'placeholder' => 'Pilih Eselon 4',
            'url' => Url::to(['/report/eselon4-list']),
            'params'=> ['input-eselon4']
        ],
    ]) ?>
    </div>
</div>
<?= Html::hiddenInput('input-eselon3', $model->eselon3, ['id'=>'input-eselon3']); ?>
<?= Html::hiddenInput('input-eselon4', $model->eselon4, ['id'=>'input-eselon4']); ?>
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
        'keterangan',
    ]
]); ?>

<?= Html::a('Export Excel', ['repday-excel', 'params'=>$model], ['class'=>'btn btn-info']); ?>&nbsp;
<?= Html::a('Export PDF', ['repday-pdf', 'params'=>$model], ['class'=>'btn btn-info']); ?>  

<p></p>

