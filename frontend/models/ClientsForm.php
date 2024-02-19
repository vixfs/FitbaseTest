<?php

namespace frontend\models;

use Yii;
use common\models\Clients;
use yii\web\UploadedFile;
use yii\db\Exception;

/**
 * This is the model class for table "client_to_clubs".
 *
 * @property int $id
 * @property int $client_id
 * @property int $club_id
 */
class ClientsForm extends Clients
{
    public $client_clubs = [];
    public $club_names = [];
    public $avatarFile;
    /*public $isDeleted;*/

        
    public function rules()
    {
        return [
            [['fio','client_clubs'], 'required'],
            [['birthday'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['fio', 'sex'], 'string', 'max' => 255],
            [['avatarFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }
    

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!parent::save($runValidation, $attributeNames)) {
                return false;
            }
            
            ClientToClubs::deleteAll(['client_id' => $this->id]);
            $this->addNewClubs();
            $this->addAvatar();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return true;
    }

    protected function addNewClubs()
    {
        foreach ($this->client_clubs as $clubId) 
        {
            $clientToClub = new ClientToClubs();
            $clientToClub->client_id = $this->id;
            $clientToClub->club_id = $clubId;
            if (!$clientToClub->save())
            {
                return 'Error while saving client to club with ID: ' . $clubId;
            }
        }
    }
   
    protected function addAvatar()
    {
        $this->avatarFile = UploadedFile::getInstance($this, 'avatarFile');
        if ($this->avatarFile) {
            $uploadPath = Yii::getAlias('@frontend/web/avatars/');
            $fileName = 'avatar_' . Yii::$app->security->generateRandomString(10) . '.' . $this->avatarFile->extension;
            $filePath = $uploadPath . $fileName;

            if ($this->avatarFile->saveAs($filePath)) {
                $this->avatar = 'avatars/' . $fileName;
                if (!$this->save(false)) 
                {
                    throw new Exception('Failed to save the model with the avatar.');
                }
            }
        } else {
            throw new Exception('Failed to save the uploaded file.');
        }
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

    public function getClubs()
    {
        return $this->hasMany(Clubs::class, ['id' => 'club_id'])
            ->viaTable('client_to_clubs', ['client_id' => 'id']);
    }
}
