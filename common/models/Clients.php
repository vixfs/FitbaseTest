<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;


/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property string $fio
 * @property string|null $sex
 * @property string|null $birthday
 * @property int $created_at
 * @property int $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    
    public static function tableName()
    {
        return 'clients';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'softDelete' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'deleted_at' => time(),
                    'deleted_by' => Yii::$app->user->id,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio'], 'required'],
            [['birthday'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['fio', 'sex','avatar'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'ФИО',
            'sex' => 'Пол',
            'birthday' => 'ДР',
            'created_at' => 'Дата создания',
            //'created_by' => 'Created By',
            //'updated_at' => 'Updated At',
            //'updated_by' => 'Updated By',
            //'deleted_at' => 'Deleted At',
            //'deleted_by' => 'Deleted By',
            'birthday_range' => 'Дата рождения',
            'club_names' => 'Клубы',
            //'avatar' => 'Аватарка'
        ];
    }
}
