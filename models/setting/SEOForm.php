<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/10
 * Time : 12:28
 */

namespace app\models\setting;


use app\components\Helper;
use yii\web\UploadedFile;

class SEOForm extends SEO
{
    public $pc_logo_file;
    public $mobile_logo_file;
    public $qrcode_file;

    public function rules()
    {
        return array_merge(parent::rules(),[
            [['pc_logo_file','mobile_logo_file','qrcode_file'], 'image', 'extensions' => 'png, jpg',
                'maxWidth' => 300,
                'maxHeight' => 300,
            ],
        ]);
    }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'pc_logo_file' => 'PC端logo',
            'mobile_logo_file' => '移动端logo',
            'qrcode_file' => '二维码',
        ]);
    }

    //保存
    public function store(){
        if(!$this->validate()) return false;

        $this->upPcLogo();

        $this->upMobilePc();

        $this->upQrcode();

        return $this->save(false);

    }

    //上传pc logo
    private function upPcLogo(){
        //是否上传图片
        $pc_logo_file = UploadedFile::getInstance($this, 'pc_logo_file');

        if($pc_logo_file){

            $old = !empty($this->pc_logo) ? $this->pc_logo : '';
            $this->pc_logo = $this->upload($pc_logo_file);

            if(!$this->pc_logo){
                $this->addError('pc_logo', '上传pc logo失败，请重试。');
                return false;
            }

            //删除原有图片
            if($old) Helper::delImage($old);

        }
        return true;
    }

    //上传 mobile logo
    private function upMobilePc(){
        $mobile_logo_file = UploadedFile::getInstance($this, 'mobile_logo_file');
        if($mobile_logo_file){

            $old = !empty($this->mobile_logo) ? $this->mobile_logo : '';
            $this->mobile_logo = $this->upload($mobile_logo_file);

            if(!$this->mobile_logo){
                $this->addError('mobile_logo', '上传mobile logo失败，请重试。');
                return false;
            }

            //删除原有图片
            if($old) Helper::delImage($old);


        }
        return true;
    }

    //上传二维码
    private function upQrcode(){
        $qrcode_file = UploadedFile::getInstance($this, 'qrcode_file');
        if($qrcode_file){

            $old = !empty($this->qrcode) ? $this->qrcode : '';
            $this->qrcode = $this->upload($qrcode_file);

            if(!$this->qrcode){
                $this->addError('qrcode', '上传二维码失败，请重试。');
                return false;
            }

            //删除原有图片
            if($old) Helper::delImage($old);
        }

        return true;
    }


    //上传操作
    private function upload( UploadedFile $file ){
        if(empty($file)) return false;

        $path = Helper::generateUploadPath('seo', $file->getExtension());


        if(empty($path['absPath']) || !$file->saveAs($path['absPath'])){
            return false;
        }

        //上传成功
        return $path['savePath'];

    }



}