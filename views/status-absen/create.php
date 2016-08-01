<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StatusAbsen */

$this->title = 'Create Status Absen';
$this->params['breadcrumbs'][] = ['label' => 'Status Absens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-absen-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
