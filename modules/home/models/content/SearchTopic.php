<?php

namespace app\modules\home\models\content;

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
            [['category_id','user_id'], 'integer'],
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
    public function search($params)
    {
        //排除 私密话题 待审核和审核失败 以及冻结的话题
        $query = Topic::find()
            ->with(['category','user'])
            ->where([
                'check' => self::CHECK_ADOPT, //审核通过的话题
                'secrecy' => self::SECR_PUBLIC//公开话题
            ])
            ->andWhere(['!=', 'status', self::STATUS_RECYCLE]); //非回收站的

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        //块赋值
        $this->attributes = $params;


        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'user_id' => $this->user_id
        ]);



        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
