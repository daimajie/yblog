<?php

namespace app\modules\home\modules\write\models;

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
        $query = Topic::find()
            ->with(['category'])
            ->andWhere(['!=', 'status', self::STATUS_RECYCLE]);//只排除回收站的话题


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);


        //以手动设置的用户id为准
        if(!empty($user_id)){
            $query->andWhere(['user_id'=>$user_id]);
            unset($params['user_id']);
        }

        //块赋值
        $this->attributes = $params;

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'secrecy' => $this->secrecy,
            'user_id' => $this->user_id  //当前用户的话题
        ]);


        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
