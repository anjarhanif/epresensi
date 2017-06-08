<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JenisJamkerja */

$this->title = 'Create Jenis Jam Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Jenis Jam Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-jamkerja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
