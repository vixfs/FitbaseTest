<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

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
    public $client_clubs;
    public $club_names;

    public static function tableName()
    {
        return 'clients';
    }

    public function getClubs()
    {
        return $this->hasMany(Clubs::class, ['id' => 'club_id'])
            ->viaTable('client_to_clubs', ['client_id' => 'id']);
    }

    public function getClientsToClubs() 
    {
        return $this->hasMany(ClientToClubs::class, ['client_id' => 'id']);
    }
    
    

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio','client_clubs'], 'required'],
            [['birthday'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['fio', 'sex'], 'string', 'max' => 255],
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
        ];
    }
}
