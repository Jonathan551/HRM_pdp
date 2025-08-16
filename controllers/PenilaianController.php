<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use app\models\MasterPenilaian;
use app\models\MasterPenilaianSearch;

class PenilaianController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new MasterPenilaianSearch();

        $query = MasterPenilaian::find()->joinWith('user');

        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['users.username' => Yii::$app->user->identity->username]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id_penilaian)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_penilaian),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = \app\models\MasterPenilaian::findOne($id)) !== null) {
            return $model;
        }

        throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
    }
}
