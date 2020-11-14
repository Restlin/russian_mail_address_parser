<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Row;
use yii\data\Sort;

/**
 * RowSearch represents the model behind the search form of `app\models\Row`.
 */
class RowSearch extends Row
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'file_id', 'status'], 'integer'],
            [['content', 'address_base', 'address_new'], 'safe'],
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
        $query = Row::find();

        // add conditions that should always apply here
        $sort = new Sort([
            'attributes' => ['id', 'file_id', 'status', 'content', 'address_base', 'address_new'],
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
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
            'file_id' => $this->file_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'address_base', $this->address_base])
            ->andFilterWhere(['ilike', 'address_new', $this->address_new]);

        return $dataProvider;
    }
}
