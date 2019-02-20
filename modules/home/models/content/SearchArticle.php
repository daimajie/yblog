<?php

namespace app\modules\home\models\content;

use app\models\content\Article;
use app\models\content\ArticleTag;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;


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
            [['topic_id'], 'integer'],
            [['title'], 'string'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Article::find()
            ->with(['user'])
            ->where([
                'check' => self::CHECK_ADOPT,
                'status' => self::STATUS_NORMAL,
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 1
            ]
        ]);


        $this->title = !empty($params['title'])?trim($params['title']):'';
        $this->topic_id = $params['id'];

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





        // grid filtering conditions
        $query->andWhere(['topic_id' => $this->topic_id]);

        $query->andWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
