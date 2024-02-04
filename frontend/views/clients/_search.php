<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;

/** @var yii\web\View $this */
/** @var frontend\models\ClientsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="clients-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['data-pjax' => true],
    ]); ?>


    <?= $form->field($model, 'fio')->textInput() ?>

    <?= $form->field($model, 'sex')->radioList(['male'=>'male', 'female'=>'female']) ?>

    <?= $form->field($model, 'birthday_range')->widget(DateRangePicker::classname(), [
    'name' => 'Дата рождения',
    'convertFormat' => true,
    'language' => 'ru',
    'pluginOptions' => [
        'timePicker' => false,
        'timePickerIncrement' => 30,
        'locale' => [
            'format' => 'Y-m-d',
            'separator' => ' to ',
        ],
    ],

]); ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
