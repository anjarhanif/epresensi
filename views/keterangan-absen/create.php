<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KeteranganAbsen */

$this->title = 'Create Keterangan Absen';
$this->params['breadcrumbs'][] = ['label' => 'Keterangan Absens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keterangan-absen-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
