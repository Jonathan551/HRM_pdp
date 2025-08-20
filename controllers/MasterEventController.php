<?php

namespace app\controllers;

use Yii;
use app\models\MasterEvent;
use app\models\MasterEventsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * MasterEventController implements the CRUD actions for MasterEvent model.
 */
class MasterEventController extends Controller
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
     * Lists all MasterEvent models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterEventsearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $models = MasterEvent::find()
            ->orderBy(['tanggal' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'models' => $models,
        ]);
    }

    /**
     * Displays a single MasterEvent model.
     * @param int $id_event Id Event
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_event)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_event),
        ]);
    }

    /**
     * Creates a new MasterEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterEvent();

        if ($model->load(Yii::$app->request->post())) {
            $this->handleUpload($model);
            if ($model->save()) {
                return $this->redirect(['view', 'id_event' => $model->id_event]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing MasterEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_event Id Event
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionUpdate($id_event)
    {
        $model = $this->findModel($id_event);
        $oldFile = $model->gambar;

        if ($model->load(Yii::$app->request->post())) {
            $this->handleUpload($model);
            if (empty($model->gambar)) {
                $model->gambar = $oldFile; 
            }
            if ($model->save(false)) {
                return $this->redirect(['view', 'id_event' => $model->id_event]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }
    /**
     * Deletes an existing MasterEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_event Id Event
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_event)
    {
        $this->findModel($id_event)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_event Id Event
     * @return MasterEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_event)
    {
        if (($model = MasterEvent::findOne(['id_event' => $id_event])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Fungsi untuk handle upload file
     * @return bool apakah berhasil upload
     */
    protected function handleUpload($model)
    {
        $file = UploadedFile::getInstance($model, 'uploadFile');
        if ($file) {
            $fileName = uniqid() . '.' . $file->extension;
            $file->saveAs(Yii::getAlias('@webroot/uploads/') . $fileName);
            $model->gambar = $fileName;   
        }
        return $model;
    }
}
