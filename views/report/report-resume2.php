<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\jui\DatePicker;
use app\models\PermissionHelpers;
use app\models\Departments;
use app\models\TglLibur;

$this->title = 'Laporan Resume';
$this->params['breadcrumbs'][]=['label'=>'Laporan Kehadiran', 'url'=>['index']];
$this->params['breadcrumbs'][]= $this->title;

if (PermissionHelpers::requireMinimumRole('AdminSKPD')) {
    $deptid = Yii::$app->user->identity->dept_id;
} else $deptid=NULL;
?>
<h1>Laporan Resume</h1>
<?php $form = ActiveForm::begin([
    'action' => ['resume-report2'],
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
    
    <?= $form->field($model,'skpd')->dropDownList(Departments::deptList(1,$deptid), [
        'prompt' => '[ Pilih SKPD ]',
        'style' => 'width:500px',
        'id' => 'skpd-id',
    ]) ?>
    <?= $form->field($model,'eselon3')->widget(DepDrop::className(), [
        'options' => ['id' => 'eselon3-id', 'style'=>'width: 500px'],
        'pluginOptions' => [
            'depends' => ['skpd-id'],
            'initialize'=>true,
            'placeholder' => '[ Pilih Eselon 3 ]',
            'url' => Url::to(['/report/eselon3-list']),
            'params' => ['input-eselon3'],
        ]
    ]) ?> 
    <?= $form->field($model,'eselon4')->widget(DepDrop::className(), [
        'options'=>['id'=>'eselon4-id','style'=>'width : 500px'],
        'pluginOptions' => [
            'depends' => ['eselon3-id'],
            'initialize'=>true,
            'placeholder' => '[ Pilih Eselon 4 ]',
            'url' => Url::to(['/report/eselon4-list']),
            'params'=> ['input-eselon4']
        ],
    ]); ?>
    </div>
</div>
<?= Html::hiddenInput('input-eselon3', $model->eselon3, ['id'=>'input-eselon3']); ?>
<?= Html::hiddenInput('input-eselon4', $model->eselon4, ['id'=>'input-eselon4']); ?>
<?= Html::submitButton('Tampilkan',['class'=>'btn btn-primary']) ?>
<p></p>
<?php ActiveForm::end(); ?>

<?php
    $tglAwal = new \DateTime($model->tglAwal);
    $tglAkhir = new \DateTime($model->tglAkhir);
    $dates = [];
    for($x = $tglAwal; $x <= $tglAkhir; $x->modify('+1 day')) {     
        $dates[] = [
            'attribute'=> $x->format('Y-m-d'),
            'label'=> $x->format('d'),
        ];
    }
    $rekap = [];
    $rekap = [
        ['attribute'=>'hadir','label'=>'H'],
        ['attribute'=>'sakit','label'=>'S'],
        ['attribute'=>'ijin','label'=>'I'],
        ['attribute'=>'cuti','label'=>'C'],
        ['attribute'=>'tugas_dinas','label'=>'TD']
    ]
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'formatter'=>['class'=>'yii\i18n\Formatter' ,'nullDisplay'=>''],
    'pager'=>[
        'firstPageLabel'=>'First',
        'lastPageLabel'=>'Last',
        'maxButtonCount'=>5
    ],
    'columns'=>  array_merge([
        ['class'=>'yii\grid\SerialColumn','contentOptions'=>['style'=>'width :5%']],
        [
            'attribute'=>'pin',
            'label'=>'PIN',
            'value'=>function ($data) {return (int)$data['pin'];},
            'contentOptions'=>['style'=>'width :7%']
        ],
        ['attribute'=>'name'],
        ], 
        $dates,
        $rekap
    )
]); ?>

<?= Html::a('Export Excel', ['represume-excel2', 'params'=>$model], ['class'=>'btn btn-info']); ?>&nbsp;
<?= Html::a('Export PDF', ['represume-pdf2', 'params'=>$model], ['class'=>'btn btn-info']); ?>  

<p></p>


