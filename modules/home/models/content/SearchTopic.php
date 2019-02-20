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
                'check' => self::CHECK_ADOPT,
                'secrecy' => self::SECR_PUBLIC
            ])
            ->andWhere(['!=', 'status', self::STATUS_RECYCLE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        //块赋值
        $this->attributes = $params;


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id
        ]);



        $query->andWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
