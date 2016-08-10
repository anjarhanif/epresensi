<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Departments */

$this->title = 'Update Unit Kerja: ' . $model->DeptID;
$this->params['breadcrumbs'][] = ['label' => 'Unit Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->DeptID, 'url' => ['view', 'id' => $model->DeptID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="departments-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
