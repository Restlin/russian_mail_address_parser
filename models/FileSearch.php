<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\File;
use yii\data\Sort;

/**
 * FileSearch represents the model behind the search form of `app\models\File`.
 */
class FileSearch extends File {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'size', 'status', 'user_id'], 'integer'],
            [['name', 'mime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = File::find();

        // add conditions that should always apply here

        $sort = new Sort([
            'attributes' => [
                'id', 'size', 'status', 'user_id', 'name', 'mime'
            ],
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
            'size' => $this->size,
            'status' => $this->status,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
                ->andFilterWhere(['ilike', 'mime', $this->mime]);

        return $dataProvider;
    }

}
