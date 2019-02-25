<?php

namespace app\modules\home\models\content;

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
            [['topic_id','category_id','user_id'], 'integer'],
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
    public function search($params)
    {
        $query = Article::find()
            ->with(['user'])
            ->where([
                'check' => self::CHECK_ADOPT, //审核通过的文章
                'status' => self::STATUS_NORMAL,//公示文章
            ]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        //**错误页(只显示最近一个月评论最多的文章)
        if(isset($params['isError']) && $params['isError']){
            $query
                ->andWhere(['>=', 'created_at', strtotime('-30day')])
                ->orderBy(['comment' => SORT_DESC]);
            return $dataProvider;
        }


        $this->attributes = $params;

        //验证文章标题数据
        if(!$this->validate()){
            return $dataProvider;
        }


        //获取定义标签的文章
        if(isset($params['tag_id']) && is_numeric($params['tag_id'])){
            $query = $query->alias('a')
                ->leftJoin(['at'=>'{{%article_tag}}'],'a.id=at.article_id')
                ->select(['a.*','at.*']);

            if($params['tag_id'] > 0){
                $query->andWhere(['tag_id'=>$params['tag_id']]);
            }else{
                $query->andWhere(['is','tag_id',null]);
            }

        }




        //必须是当前话题的文章
        $query->andFilterWhere(['topic_id' => $this->topic_id]);

        $query->andFilterWhere(['user_id' => $this->user_id]);

        $query->andFilterWhere(['category_id' => $this->category_id]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
