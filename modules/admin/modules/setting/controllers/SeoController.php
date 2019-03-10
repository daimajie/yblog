<?php

namespace app\modules\admin\modules\setting\controllers;

use app\models\setting\SEOForm;
use Yii;
use app\modules\admin\controllers\BaseController;
use yii\web\NotFoundHttpException;

/**
 * SEOController implements the CRUD actions for SEO model.
 */
class SeoController extends BaseController
{


    /**
     * Lists all SEO models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post()) && $model->store()) {
            Yii::$app->session->setFlash('success','设置成功。');
            return $this->refresh();
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Finds the SEO model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SEOForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel()
    {
        if (($model = SEOForm::find()->limit(1)->one()) !== null) {
            return $model;
        } else {
            return new SEOForm();
        }
    }
}
