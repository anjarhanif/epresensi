<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TglLibur */

$this->title = 'Create Tgl Libur';
$this->params['breadcrumbs'][] = ['label' => 'Tgl Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tgl-libur-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
