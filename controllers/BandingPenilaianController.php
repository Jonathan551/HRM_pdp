<?php

namespace app\controllers;

use Yii;
use app\models\BandingPenilaian;
use app\models\BandingPenilaiansearch;
use app\models\MasterPenilaian;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BandingPenilaianController implements the CRUD actions for BandingPenilaian model.
 */
class BandingPenilaianController extends Controller
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
     * Lists all BandingPenilaian models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BandingPenilaiansearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BandingPenilaian model.
     * @param int $id_banding Id Banding
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_banding)
    {   
        return $this->render('view', [
            'model' => $this->findModel($id_banding),
        ]);
    }

   public function actionReview($id_banding)
    {
        $model = $this->findModel($id_banding);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $submitBtn = Yii::$app->request->post('submitBtn');
                $model->tanggal_review = date('Y-m-d H:i:s');

                if ($submitBtn === 'tolak') {
                    $model->status = 'Ditolak';
                    if ($model->save(false)) {
                        return $this->redirect(['banding-penilaian/index']);
                    }
                }

                if ($submitBtn === 'terima') {
                    $model->status = 'Diterima';
                    if ($model->save(false)) {
                        return $this->redirect([
                            'master-penilaian/update',
                            'id_penilaian' => $model->id_penilaian
                        ]);
                    }
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Review berhasil disimpan');
                    return $this->redirect(['view', 'id_banding' => $model->id_banding]);
                }
            }
        }

        return $this->render('review', [
            'model' => $model,
        ]);
    }

    public function actionBanding($id_banding)
    {
        return $this->render('banding', [
            'model' => $this->findModel($id_banding),
        ]);
    }

    /**
     * Creates a new BandingPenilaian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_penilaian = null)
    {
        $model = new BandingPenilaian();

        if ($id_penilaian !== null) {
            $model->id_penilaian = $id_penilaian;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_banding' => $model->id_banding]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BandingPenilaian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_banding Id Banding
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_banding)
    {
        $model = $this->findModel($id_banding);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_banding' => $model->id_banding]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BandingPenilaian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_banding Id Banding
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_banding)
    {
        $this->findModel($id_banding)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BandingPenilaian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_banding Id Banding
     * @return BandingPenilaian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_banding)
    {
        if (($model = BandingPenilaian::findOne(['id_banding' => $id_banding])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

     public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
                foreach (['tanggal_banding','tanggal_review'] as $attr) {
                    if (!empty($this->$attr) && preg_match('/\d{2}-\d{2}-\d{4}/', $this->$attr)) {
                        if (strpos($this->$attr, ':') !== false) {
                            $this->$attr = Yii::$app->formatter->asDatetime($this->$attr, 'php:Y-m-d H:i:s');
                        } else {
                            $this->$attr = Yii::$app->formatter->asDate($this->$attr, 'php:Y-m-d');
                        }
                }
                 return true;
            }
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();

        foreach (['tanggal_banding', 'tanggal_review'] as $attr) {
            if (!empty($this->$attr) && $this->$attr != '0000-00-00' && $this->$attr != '0000-00-00 00:00:00') {
                if (strpos($this->$attr, ':') !== false) {
                    $this->$attr = Yii::$app->formatter->asDatetime($this->$attr, 'php:d-m-Y H:i');
                } else {
                    $this->$attr = Yii::$app->formatter->asDate($this->$attr, 'php:d-m-Y');
                }
            }
        }
    }
}
