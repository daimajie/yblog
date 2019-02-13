<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Topic;

/**
 * SearchTopic represents the model behind the search form of `app\modules\admin\models\Topic`.
 */
class SearchTopic extends Topic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'check', 'secrecy','category_id'], 'integer'],
            [['name'], 'safe'],
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
        $query = Topic::find()->with(['category'/*,'user'*/]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        //排除回收站数据 或只显示回收站数据
        if(!isset($params['status']))
            $query->andFilterWhere(['!=', 'status', Topic::STATUS_RECYCLE]);
        if(isset($params['status']) && (int)$params['status']===Topic::STATUS_RECYCLE){

            $this->status = $params['status'];
            $query->andFilterWhere(['status' => Topic::STATUS_RECYCLE]);
        }




        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'status' => $this->status ,
            'check' => $this->check,
            'secrecy' => $this->secrecy,
            'category_id' => $this->category_id,
        ]);



        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
