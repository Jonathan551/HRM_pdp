<?php

namespace app\controllers;

use Yii;
use app\models\MasterPenilaian;
use yii\helpers\ArrayHelper;
use app\models\DetailPenilaian;
use app\models\MasterPenilaiansearch;
use app\components\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterPenilaianController implements the CRUD actions for MasterPenilaian model.
 */
class MasterPenilaianController extends Controller
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
     * Lists all MasterPenilaian models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterPenilaiansearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterPenilaian model.
     * @param int $id_penilaian Id Penilaian
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_penilaian)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_penilaian),
        ]);
    }

    /**
     * Creates a new MasterPenilaian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterPenilaian();
        $details = [new DetailPenilaian()];

        if ($this->processForm($model, $details)) {
            return $this->redirect(['view', 'id_penilaian' => $model->id_penilaian]);
        }

        return $this->render('create', [
            'model' => $model,
            'detailModels' => $details,
        ]);
    }

    /**
     * Updates an existing MasterPenilaian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_penilaian Id Penilaian
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    public function actionUpdate($id_penilaian)
    {
        $model = MasterPenilaian::findOne($id_penilaian);
        $details = $model->detailPenilaian;

        if ($this->processForm($model, $details)) {
            return $this->redirect(['view', 'id_penilaian' => $model->id_penilaian]);
        }

        return $this->render('update', [
            'model' => $model,
            'detailModels' => $details,
        ]);
    }

    /**
     * Deletes an existing MasterPenilaian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_penilaian Id Penilaian
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_penilaian)
    {
        $this->findModel($id_penilaian)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterPenilaian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_penilaian Id Penilaian
     * @return MasterPenilaian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_penilaian)
    {
        if (($model = MasterPenilaian::findOne(['id_penilaian' => $id_penilaian])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

   private function processForm($model, &$details)
    {
        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($details, 'id_detailpenilaian', 'id_detailpenilaian');
            $details = Model::createMultiple(DetailPenilaian::class, $details);
            Model::loadMultiple($details, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($details, 'id_detailpenilaian', 'id_detailpenilaian')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($details) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) {

                        if (!empty($deletedIDs)) {
                            DetailPenilaian::deleteAll(['id_detailpenilaian' => $deletedIDs]);
                        }

                        foreach ($details as $detail) {
                            $detail->id_penilaian = $model->id_penilaian;
                            if (! $detail->save(false)) {
                                $transaction->rollBack();
                                return false;
                            }
                        }

                        $transaction->commit();
                        return true;
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }
        return false;
    }

    public function actionListAnchor($id_kriteria)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $anchors = \app\models\MasterAnchor::find()
            ->where(['id_kriteria' => $id_kriteria])
            ->all();

        return \yii\helpers\ArrayHelper::map($anchors, 'id_anchor', function ($model) {
            $bobot = $model->kriteria ? $model->kriteria->bobot : '-';
            return $model->level_anchor 
                . ' - ' . $model->deskripsi 
                . ' (' . $model->nilai_anchor . ')'
                . ' | Bobot: ' . $bobot;
        });
    }
}
