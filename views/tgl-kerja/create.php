<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TglKerja */

$this->title = 'Create Tgl Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Tgl Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tgl-kerja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
