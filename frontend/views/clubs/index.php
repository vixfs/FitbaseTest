<?php

use frontend\models\Clubs;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\ClubsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Клубы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="clubs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php Pjax::begin(['id' => 'grid-pjax']); ?>

    <?= $this->render('_search', ['model' => $searchModel]); ?>
    
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                'name',
                'address',
                [
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return Yii::$app->formatter->asDate($model->created_at, 'yyyy-MM-dd');
                    },
                ],
                //'created_by',
                //'updated_at',
                //'updated_by',
                //'deleted_at',
                //'deleted_by',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Clubs $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
    

</div>
