<?php

use frontend\models\ClientsForm;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;


/** @var yii\web\View $this */
/** @var frontend\models\ClientsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clients-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <button id="showNotificationBtn">Показать уведомление</button>
    
    <?php
        $this->registerJs(
            "$('#showNotificationBtn').click(function() {
                $.ajax({
                    url: '/clients/show-notification',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        new PNotify({
                            title: data.title,
                            text: data.text,
                            type: 'success'
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Ошибка:', error);
                    }
                });
            });",
            View::POS_READY,
            'my-button-handler'
        );
    ?>
    
    <?php Pjax::begin(['id' => 'grid-pjax']); ?>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'avatar',
                'format' => 'html',
                'value' => function($model) 
                {
                    if ($model->avatar){
                        return Html::img(Yii::getAlias('@web') . '/' . $model->avatar, ['alt' => $model->avatar, 'style' => 'width:50px']);
                    } else {
                        return Html::img(Yii::getAlias('@web') . '/avatars/default_avatar.jpg', ['alt' => 'avatar', 'style' => 'width:50px']);
                    }
                    
                },
            ],
            'id',
            'fio',
            'sex',
            'birthday',
            [
                'attribute' => 'created_at',
                'value' => function($model)
                {
                    return Yii::$app->formatter->asDate($model->created_at, 'yyyy-MM-dd');
                },
            ],
            [
                'attribute' => 'club_names',
                'format' => 'text',
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ClientsForm $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>


</div>
