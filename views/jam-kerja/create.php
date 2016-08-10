<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JamKerja */

$this->title = 'Create Jam Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
