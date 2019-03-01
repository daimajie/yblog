<?php

namespace app\modules\home\modules\content\models;

use app\models\content\Topic;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
            [['category_id','secrecy'], 'integer'],
            [['name'], 'string'],
            [['name'], 'trim'],
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
    public function search($params, $user_id=null)
    {
        //排除 私密话题 待审核和审核失败 以及冻结的话题
        $query = Topic::find()
            ->with(['category'])
            ->andWhere(['!=', 'status', self::STATUS_RECYCLE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        //块赋值
        $this->attributes = $params;

        //如果传递用户id就以传递的为准
        if (!empty($user_id))
            $this->user_id = $user_id;


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'secrecy' => $this->secrecy
        ]);



        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
