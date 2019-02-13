<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/25
 * Time : 14:09
 */

namespace app\modules\admin\models\content;


use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Json;

class ArticleForm extends ActiveRecord
{
    public $new_tags = '';
    public $arr_tags = '';
    public $art_content = '';

    public function rules()
    {
        return [
            [['art_content'], 'required'],

            [['new_tags'], 'string', 'max'=>18],
            [['arr_tags'], 'checkTag'],
            [['art_content'], 'string', 'min'=>5],
        ];
    }

    /**
     * #检测选中的标签是否属于当前选中的话题,(文章属于一个话题，文章关联的标签也应该属于该话题)
     * @param $attr
     * @return bool
     */
    public function checkTag($attr){

        //检测是否有错误
        if ($this->hasErrors())
            return false;

        //判断数据类型
        if (!is_array($this->arr_tags)){
            $this->addError($attr, '请正确选择可选标签。');
            return false;
        }

        //获取标签所属话题
        $topic_ids = Tag::find()
            ->select(['topic_id'])
            ->where(['in', 'id', $this->arr_tags])
            ->asArray()
            ->column();
        $topic_id = array_unique($topic_ids);


        if(count($topic_id) <= 0 || $topic_id[0] != $this->topic_id){

            $this->addError($attr, '选择可用标签有误，请重新选择。');
            return false;
        }

        return true;

    }

    public function attributeLabels()
    {
        return [
            'new_tags' => '新建标签',
            'arr_tags' => '可选标签',
            'art_content' => '文章内容'
        ];
    }

    /**
     * #创建新标签
     * @param $str
     * @param $topic_id
     * @return array
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public static function createNewTags($str, $topic_id){
        $ret = [];

        //验证数据
        if(empty($str) || empty($topic_id))
            return $ret;


        $str = str_replace('，', ',', trim($str));
        $arr = explode(',', $str);


        //创建文章时 可同时创建多少个新标签
        $createLimit = (int) Yii::$app->params['tag']['createLimit'];
        if($createLimit <= 0)
            throw new InvalidConfigException('tag create limit error');

        if (count($arr) > $createLimit)
            throw new Exception('创建文章时最多同时创建'.$createLimit.'个新标签.');

        //检测是否达到上限
        $diff = Tag::checkLimit($topic_id);
        if($diff < count($arr))
            throw new Exception('当前话题最多能再创建' . $diff . '个标签。');


        //建立标签
        foreach ($arr as $v) {
            $model = new Tag();
            $model->name = $v;
            $model->topic_id = $topic_id;

            if(!$model->save()){
                throw new Exception('新建标签失败：' . Json::encode($model->getErrorSummary(true)));
            }

            array_push($ret, $model->id);

        }

        return $ret;
    }
}