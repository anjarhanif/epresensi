<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Checkinout */

$this->title = 'Create Checkinout';
$this->params['breadcrumbs'][] = ['label' => 'Checkinouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="checkinout-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
