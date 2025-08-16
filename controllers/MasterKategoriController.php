<?php

namespace app\controllers;

use app\models\MasterKategori;
use app\models\MasterKategorisearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterKategoriController implements the CRUD actions for MasterKategori model.
 */
class MasterKategoriController extends Controller
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
     * Lists all MasterKategori models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterKategorisearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterKategori model.
     * @param int $id_kategori Id Kategori
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_kategori)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_kategori),
        ]);
    }

    /**
     * Creates a new MasterKategori model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterKategori();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_kategori' => $model->id_kategori]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterKategori model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_kategori Id Kategori
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_kategori)
    {
        $model = $this->findModel($id_kategori);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_kategori' => $model->id_kategori]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterKategori model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_kategori Id Kategori
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_kategori)
    {
        $this->findModel($id_kategori)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterKategori model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_kategori Id Kategori
     * @return MasterKategori the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_kategori)
    {
        if (($model = MasterKategori::findOne(['id_kategori' => $id_kategori])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
