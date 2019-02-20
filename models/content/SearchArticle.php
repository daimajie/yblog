<?php

namespace app\models\content;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * SearchArticle represents the model behind the search form of `app\modules\admin\models\Article`.
 */
class SearchArticle extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'check','topic_id', 'user_id'], 'integer'],
            [['status','check'],'in', 'range' => [1,2,3]],
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
        $query = Article::find()->with(['topic', 'user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        //状态信息赋值
        $this->status = isset($params['status']) ? $params['status'] : Article::STATUS_NORMAL;

        $this->load($params);



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
            'check' => $this->check,
            'topic_id' => $this->topic_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        return $dataProvider;
    }
}
