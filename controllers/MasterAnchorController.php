<?php

namespace app\controllers;

use app\models\MasterAnchor;
use app\models\MasterAnchorsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterAnchorController implements the CRUD actions for MasterAnchor model.
 */
class MasterAnchorController extends Controller
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
     * Lists all MasterAnchor models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterAnchorsearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterAnchor model.
     * @param int $id_anchor Id Anchor
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_anchor)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_anchor),
        ]);
    }

    /**
     * Creates a new MasterAnchor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterAnchor();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_anchor' => $model->id_anchor]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterAnchor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_anchor Id Anchor
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_anchor)
    {
        $model = $this->findModel($id_anchor);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_anchor' => $model->id_anchor]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterAnchor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_anchor Id Anchor
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_anchor)
    {
        $this->findModel($id_anchor)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterAnchor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_anchor Id Anchor
     * @return MasterAnchor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_anchor)
    {
        if (($model = MasterAnchor::findOne(['id_anchor' => $id_anchor])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
