<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'email_code_unixtime', 'pwd_reset_token_unixtime'], 'integer'],
            [['surname', 'name', 'patronymic', 'phone', 'email', 'no_confirm_email', 'email_code', 'password_hash', 'pwd_reset_token'], 'safe'],
            [['active'], 'boolean'],
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
        $query = User::find();

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
            'email_code_unixtime' => $this->email_code_unixtime,
            'pwd_reset_token_unixtime' => $this->pwd_reset_token_unixtime,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['ilike', 'surname', $this->surname])
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'patronymic', $this->patronymic])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'no_confirm_email', $this->no_confirm_email])
            ->andFilterWhere(['ilike', 'email_code', $this->email_code])
            ->andFilterWhere(['ilike', 'password_hash', $this->password_hash])
            ->andFilterWhere(['ilike', 'pwd_reset_token', $this->pwd_reset_token]);

        return $dataProvider;
    }
}
