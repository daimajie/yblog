<?php

namespace app\models\motion;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\motion\Comment;

/**
 * SearchComment represents the model behind the search form of `app\models\motion\Comment`.
 */
class SearchComment extends Comment
{
    public $start_time;
    public $end_time;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /*[['id', 'parent_id', 'user_id', 'created_at'], 'integer'],
            [['content'], 'safe'],*/

            [['end_time', 'start_time'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Comment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if(!empty($this->start_time) && !empty($this->end_time)){
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->start_time)]);
            $query->andFilterWhere(['<=', 'created_at', strtotime($this->end_time)]);
        }

        return $dataProvider;
    }
}
