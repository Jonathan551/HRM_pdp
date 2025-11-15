<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;
use app\models\MasterPenilaian;
use app\models\MasterPenilaianSearch;
use app\models\BandingPenilaian;

class PengajuanBandingController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new MasterPenilaianSearch();

        $query = MasterPenilaian::find()->joinWith(['user u']);

        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['u.username' => Yii::$app->user->identity->username]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView(?int $id_banding = null, ?int $id_penilaian = null)
    {
        if ($id_banding !== null) {
            $model = $this->findBanding($id_banding);
            return $this->render('view-banding', ['model' => $model]);
        }

        if ($id_penilaian !== null) {
            $model = $this->findPenilaian($id_penilaian);
            return $this->render('view', ['model' => $model]);
        }

        throw new BadRequestHttpException('Parameter tidak lengkap.');
    }

    public function actionCreate($id_penilaian = null)
    {
        $model = new BandingPenilaian();
        if ($id_penilaian !== null) {
            $model->id_penilaian = $id_penilaian;
        }

        $model->id_users = Yii::$app->user->id;

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Banding diajukan.');
                return $this->redirect(['view', 'id_banding' => $model->id_banding]); // sekarang valid
            }
            Yii::$app->session->setFlash('error', reset($model->firstErrors) ?: 'Gagal menyimpan.');
        }

        return $this->render('create', compact('model'));
    }

    protected function findPenilaian(int $id): MasterPenilaian
    {
        if (($m = MasterPenilaian::findOne($id)) !== null) {
            return $m;
        }
        throw new NotFoundHttpException('Penilaian tidak ditemukan.');
    }

    protected function findBanding(int $id): BandingPenilaian
    {
        if (($m = BandingPenilaian::findOne($id)) !== null) {
            return $m;
        }
        throw new NotFoundHttpException('Pengajuan banding tidak ditemukan.');
    }
}
