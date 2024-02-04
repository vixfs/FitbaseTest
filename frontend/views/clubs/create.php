<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Clubs $model */

$this->title = 'Create Clubs';
$this->params['breadcrumbs'][] = ['label' => 'Clubs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clubs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
