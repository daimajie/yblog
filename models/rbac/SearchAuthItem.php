<?php
namespace app\models\rbac;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchTopic represents the model behind the search form of `app\modules\admin\models\Topic`.
 */
class SearchAuthItem extends AuthItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'in', 'range' => [1, 2]],
            [['name'], 'string', 'max'=>62],
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
        $query = self::find()->with(['ruleName']);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);


        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere([
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
