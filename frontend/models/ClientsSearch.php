<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ClientsForm;

/**
 * ClientsSearch represents the model behind the search form of `frontend\models\Clients`.
 */
class ClientsSearch extends ClientsForm
{
    /**
     * {@inheritdoc}
     */
    public $showDeleted = 0;
    public $birthday_range;

    public function rules()
    {
        return [
            [['id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['fio', 'sex', 'birthday','birthday_range'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ClientsForm::find()->joinWith('clubs');
        $query->select([
            'clients.*',
            'GROUP_CONCAT(clubs.name SEPARATOR ", ") AS club_names' // Ensure the comma and space are in single quotes 
        ]);
        
        $query->groupBy('clients.id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        

        $query->andFilterWhere([
            'id' => $this->id,
            'birthday' => $this->birthday,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        
        if ($this->showDeleted) {
            $query->andWhere(['not', ['clients.deleted_by' => null]]);
        } else {
            $query->andWhere(['clients.deleted_by' => null]);
        }

        $query->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['=', 'sex', $this->sex])
            ->andFilterWhere(['between', 'birthday', $this->parseStartDate(), $this->parseEndDate()]);

        return $dataProvider;
    }

    protected function parseStartDate()
    {
        $dateRange = $this->birthday_range;
        if ($dateRange !== null) {
            $dates = explode(' to ', $dateRange, 2);
            $startDate = isset($dates[0]) ? trim($dates[0]) : null;
            return $startDate ? Yii::$app->formatter->asDate($startDate, 'php:Y-m-d') : null;
        }
        return null; // return null if $dateRange is null
    }

    protected function parseEndDate()
    {
        $dateRange = $this->birthday_range;
        if ($dateRange !== null) {
            $dates = explode(' to ', $dateRange, 2);
            $endDate = isset($dates[1]) ? trim($dates[1]) : null;
            return $endDate ? Yii::$app->formatter->asDate($endDate, 'php:Y-m-d') : null;
        }
        return null; // return null if $dateRange is null
    }

}
