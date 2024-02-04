<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_to_clubs".
 *
 * @property int $id
 * @property int $client_id
 * @property int $club_id
 */
class ClientToClubs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_to_clubs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'club_id'], 'required'],
            [['client_id', 'club_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => 'Client ID',
            'club_id' => 'Club ID',
        ];
    }
}
