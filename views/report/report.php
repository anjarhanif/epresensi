<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

?>
<h1>Laporan Harian</h1>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model,'skpd') ?>
<?= $form->field($model,'eselon3') ?>
<?= $form->field($model,'eselon4') ?>
<?= $form->field($model,'tanggal') ?>
<?= Html::submitButton('Tampilkan',['class'=>'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'yii\grid\SerialColumn'],
        
    ]
]);