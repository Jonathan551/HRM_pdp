<?php

namespace app\controllers;

use Yii;
use app\models\MasterPeriode;
use app\models\MasterPeriodesearch;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterPeriodeController implements the CRUD actions for MasterPeriode model.
 */
class MasterPeriodeController extends BaseController
{
    /**
     * @inheritDoc
     */
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

    /**
     * Lists all MasterPeriode models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterPeriodesearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterPeriode model.
     * @param int $id_periode Id Periode
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_periode)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_periode),
        ]);
    }

    /**
     * Creates a new MasterPeriode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    $model = new MasterPeriode();
    if ($model->load(Yii::$app->request->post())) {
        $model->id_user = Yii::$app->user->id;
        if ($model->save()) {
            Yii::$app->session->setFlash('success','Periode dibuat & penilaian otomatis digenerate.');
            return $this->redirect(['master-penilaian/index','id_periode' => $model->id_periode]);
        }
        Yii::$app->session->setFlash('error','Gagal simpan: '.json_encode($model->errors));
    }
    return $this->render('create', ['model'=>$model]);
}

    /**
     * Updates an existing MasterPeriode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_periode Id Periode
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_periode)
    {
        $model = $this->findModel($id_periode);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_periode' => $model->id_periode]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterPeriode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_periode Id Periode
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_periode)
    {
        $this->findModel($id_periode)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterPeriode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_periode Id Periode
     * @return MasterPeriode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_periode)
    {
        if (($model = MasterPeriode::findOne(['id_periode' => $id_periode])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
