<?php

namespace frontend\models;

use Yii;
use common\models\Clients;
use yii\web\UploadedFile;

/**
 * This is the model class for table "client_to_clubs".
 *
 * @property int $id
 * @property int $client_id
 * @property int $club_id
 */
class ClientsForm extends Clients
{
    public $client_clubs;
    public $club_names;
    public $avatarFile;

    public function getClubs()
    {
        return $this->hasMany(Clubs::class, ['id' => 'club_id'])
            ->viaTable('client_to_clubs', ['client_id' => 'id']);
    }
    
    public function rules()
    {
        return [
            [['fio','client_clubs'], 'required'],
            [['birthday'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['fio', 'sex','avatar'], 'string', 'max' => 255],
            [['avatarFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function getClubsList()
    {
        $clubs = Clubs::find()->all();
        $clubList = [];
        
        foreach ($clubs as $club) {
            $clubList[$club->id] = $club->name;
        }
        
        return $clubList;
    }
}
