<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use frontend\models\Clubs;

/** @var yii\web\View $this */
/** @var frontend\models\Clients $model */
/** @var yii\widgets\ActiveForm $form */

/* $clubs = ArrayHelper::map(Clubs::find()->all(), 'id', 'name'); */
?>

<div class="clients-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sex')->radioList(['male'=>'male', 'female'=>'female']) ?>

    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
        'name' => 'setBirthday',
        'type' => DatePicker::TYPE_INPUT,
        'value' => '01-01-2000',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ],
    ]); ?>

<?= $form->field($model, 'client_clubs')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(Clubs::find()->all(), 'id', 'name'),
    'options' => ['multiple' => true, 'placeholder' => 'Select clubs'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]) ?>
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
