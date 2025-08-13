<?php

namespace app\controllers;

use app\models\MasterJabatan;
use app\models\MasterJabatansearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterJabatanController implements the CRUD actions for MasterJabatan model.
 */
class MasterJabatanController extends Controller
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
     * Lists all MasterJabatan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterJabatansearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterJabatan model.
     * @param int $id_jabatan Id Jabatan
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_jabatan)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_jabatan),
        ]);
    }

    /**
     * Creates a new MasterJabatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterJabatan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_jabatan' => $model->id_jabatan]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterJabatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_jabatan Id Jabatan
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_jabatan)
    {
        $model = $this->findModel($id_jabatan);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_jabatan' => $model->id_jabatan]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterJabatan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_jabatan Id Jabatan
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_jabatan)
    {
        $this->findModel($id_jabatan)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterJabatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_jabatan Id Jabatan
     * @return MasterJabatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_jabatan)
    {
        if (($model = MasterJabatan::findOne(['id_jabatan' => $id_jabatan])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
