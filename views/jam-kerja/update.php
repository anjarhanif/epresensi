<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JamKerja */

$this->title = 'Update Jam Kerja: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jam-kerja-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
