<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Row;
use yii\data\Sort;
use yii\db\Expression;

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

        $query->addSelect(["*", new Expression('CASE WHEN status = :done THEN 1 ELSE 0 END AS "customStatusSort"', ['done' => Row::STATUS_DONE])]);

        // add conditions that should always apply here
        $sort = new Sort([
            'attributes' => [
                'id',
                'file_id',
                'status' => [
                    'asc' => ['customStatusSort' => SORT_DESC, 'status' => SORT_ASC],
                    'desc' => ['customStatusSort' => SORT_DESC, 'status' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
                'content',
                'address_base',
                'address_new',
                'customStatusSort',
            ],
            'defaultOrder' => ['status' => SORT_DESC, 'id' => SORT_DESC],
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

    public function getAvgSpeed()
    {
        $connection = \Yii::$app->db;
        $command = $connection->createCommand("select avg(t.tm) from (
            select count(row.*) / extract('epoch' from age(file.date_end, file.date_start)) tm
            from row
            join file on file.id = row.file_id
            group by file.id
        ) t");
        $result = $command->queryOne();
        return $result['avg'] ?? 0;
    }
}
