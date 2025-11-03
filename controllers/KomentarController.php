<?php

namespace app\controllers;

use Yii;    
use yii\filters\VerbFilter;
use app\models\Komentar;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

Class KomentarController extends Controller
{

    public function behaviors()
    {
            return array_merge(
                parent::behaviors(),
                [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ]
            );
    }

   public function actionCreate($id_event)
   {
    $model = new Komentar([
        'id_event' => (int)$id_event,
        'id_users' => Yii::$app->user->identity->id_users ?? null,
    ]);

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_create_success'); 
        }
        return $this->redirect(['master-event/view', 'id_event' => $id_event]);
    }

    if (Yii::$app->request->isAjax) {
        return $this->renderAjax('_form', ['model' => $model]);
    }
    return $this->render('create', ['model' => $model]);
   }
}

