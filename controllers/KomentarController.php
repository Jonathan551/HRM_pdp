<?php

namespace app\controllers;

use Yii;    
use yii\filters\VerbFilter;
use app\models\Komentar;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
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

   public function actionUpdate(int $id)
    {
        $model = Komentar::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Komentar tidak ditemukan');
        }

        $uid = Yii::$app->user->identity->id_users ?? null;
        if (!$uid || (int)$uid !== (int)$model->id_users) {
            throw new ForbiddenHttpException('Anda tidak berhak mengedit komentar ini.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                $this->layout = false;
                return $this->renderPartial('@app/views/komentar/_item', ['model' => $model]);
            }
            return $this->redirect(['master-event/view', 'id_event' => $model->id_event]);
        }

        if (Yii::$app->request->isAjax) {
            $this->layout = false;
            return $this->renderPartial('_form_edit', ['model' => $model]);
        }

        return $this->render('update', ['model' => $model]);
    }
}

