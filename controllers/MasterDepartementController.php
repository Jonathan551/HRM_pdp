<?php

namespace app\controllers;

use app\models\MasterDepartement;
use app\models\MasterDepartementsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterDepartementController implements the CRUD actions for MasterDepartement model.
 */
class MasterDepartementController extends Controller
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
     * Lists all MasterDepartement models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterDepartementsearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterDepartement model.
     * @param int $id_departement Id Departement
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_departement)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_departement),
        ]);
    }

    /**
     * Creates a new MasterDepartement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterDepartement();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_departement' => $model->id_departement]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterDepartement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_departement Id Departement
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_departement)
    {
        $model = $this->findModel($id_departement);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_departement' => $model->id_departement]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterDepartement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_departement Id Departement
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_departement)
    {
        $this->findModel($id_departement)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterDepartement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_departement Id Departement
     * @return MasterDepartement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_departement)
    {
        if (($model = MasterDepartement::findOne(['id_departement' => $id_departement])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
