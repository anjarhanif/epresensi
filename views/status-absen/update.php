<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StatusAbsen */

$this->title = 'Update Status Absen: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Status Absen', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="status-absen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
