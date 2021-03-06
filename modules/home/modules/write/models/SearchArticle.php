<?php

namespace app\modules\home\modules\write\models;

use app\models\content\Article;
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
            [['topic_id','category_id','status','check','user_id'], 'integer'],
            [['status', 'check'], 'in', 'range' => [1,2]],
            [['title'], 'string', 'max' => 32],
            [['title'], 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     */
    public function search($params, $topic_id=null, $user_id=null)
    {
        $query = Article::find()
            ->andWhere(['!=', 'status', self::STATUS_RECYCLE]);//排除回收站的话题

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        //以手动设置的为准
        if(!empty($topic_id)){
            $query->andWhere(['topic_id'=>$topic_id]);
            unset($params['topic_id']);
        }

        //以手动设置的为准
        if(!empty($user_id)){
            $query->andWhere(['user_id'=>$user_id]);
            unset($params['user_id']);
        }

        $this->attributes = $params;

        //验证文章标题数据
        if(!$this->validate()){
            return $dataProvider;
        }


        //必须是当前话题的文章
        $query->andFilterWhere(['user_id' => $this->user_id]);

        $query->andFilterWhere(['category_id' => $this->category_id]);

        $query->andFilterWhere(['status' => $this->status]);

        $query->andFilterWhere(['check' => $this->check]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
