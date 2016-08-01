<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TglLibur */

$this->title = 'Update Tgl Libur: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tgl Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tgl-libur-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
