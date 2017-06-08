<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JenisJamkerja */

$this->title = 'Update Jenis Jam kerja: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Jam kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jenis-jamkerja-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
