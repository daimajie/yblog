<?php

namespace app\modules\admin\modules\setting\controllers;

use Yii;
use app\models\setting\Advert;
use yii\data\ActiveDataProvider;
use app\modules\admin\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvertController implements the CRUD actions for Advert model.
 */
class AdvertController extends BaseController
{


    /**
     * Creates a new Advert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Finds the Advert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Advert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel()
    {
        if (($model = Advert::find()->limit(1)->one()) !== null) {
            return $model;
        } else {
            $model = new Advert();
            $model->switch = 1;
            return $model;
        }
    }
}
