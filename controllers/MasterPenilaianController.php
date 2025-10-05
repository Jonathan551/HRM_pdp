<?php
// File: controllers/MasterPenilaianController.php
namespace app\controllers;

use Yii;
use yii\web\Controller; 
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\MasterPenilaian;
use app\models\MasterPenilaiansearch;
use app\models\DetailPenilaian;
use app\models\MasterKriteria;
use app\models\MasterAnchor;
use app\models\User;
use app\components\Model;

class MasterPenilaianController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new MasterPenilaiansearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
        ]);
    }

    public function actionView($id_penilaian)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_penilaian),
        ]);
    }

    public function actionViewModal($id_penilaian)
    {
        $this->layout = false;
        return $this->render('view-modal', [
            'model' => $this->findModel($id_penilaian),
        ]);
    }

    public function actionCreate()
    {
        $model   = new MasterPenilaian();
        $details = [new DetailPenilaian()];

        if ($this->processForm($model, $details)) {
            return $this->redirect(['view', 'id_penilaian' => $model->id_penilaian]);
        }

        return $this->render('create', [
            'model'        => $model,
            'detailModels' => $details,
        ]);
    }

    public function actionUpdate($id_penilaian)
    {
        $model   = $this->findModel($id_penilaian);
        $details = $model->detailPenilaian ?: [new DetailPenilaian()];

        if ($this->processForm($model, $details)) {
            return $this->redirect(['view', 'id_penilaian' => $model->id_penilaian]);
        }

        return $this->render('update', [
            'model'        => $model,
            'detailModels' => $details,
        ]);
    }

    public function actionDelete($id_penilaian)
    {
        $this->findModel($id_penilaian)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id_penilaian)
    {
        if (($model = MasterPenilaian::findOne(['id_penilaian' => $id_penilaian])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    private function processForm(MasterPenilaian $model, array &$details): bool
    {
        if (!$model->load(Yii::$app->request->post())) {
            return false;
        }

        $oldIDs = !$model->isNewRecord
            ? ArrayHelper::map($details, 'id_detailpenilaian', 'id_detailpenilaian')
            : [];

        $details = $this->buildDetailsFromPost($details);

        if (property_exists($model, 'detailModels')) {
            $model->detailModels = $details;
        }

        if (!$this->validateRequiredDetails($model, $details)) {
            $details = $details ?: [new DetailPenilaian()];
            return false;
        }

        $deletedIDs = $this->computeDeletedIds($model, $oldIDs, $details);

        $valid = $this->validateMasterAndDetails($model, $details);
        $valid = $this->validateAnchorOwnership($details) && $valid;

        if (!$valid) {
            return false;
        }

        return $this->saveWithTransaction($model, $details, $deletedIDs);
    }


    private function buildDetailsFromPost(array $existingDetails): array
    {
        $details = Model::createMultiple(DetailPenilaian::class, $existingDetails);
        Model::loadMultiple($details, Yii::$app->request->post());

        $details = array_values(array_filter(
            $details,
            static function (DetailPenilaian $d): bool {
                return !(
                    ($d->id_kriteria === null ) &&
                    ($d->id_anchor   === null )
                );
            }
        ));

        return $details;
    }


    private function computeDeletedIds(MasterPenilaian $model, array $oldIDs, array $details): array
    {
        if ($model->isNewRecord) {
            return [];
        }
        $currentIDs = array_filter(ArrayHelper::map($details, 'id_detailpenilaian', 'id_detailpenilaian'));
        return array_diff($oldIDs, $currentIDs);
    }


    private function validateRequiredDetails(MasterPenilaian $model, array $details): bool
    {
        if (count($details) === 0) {
            // Kenapa: mencegah simpan tanpa detail
            $model->addError('id_penilaian', 'Minimal 1 baris Detail Penilaian wajib diisi.');
            Yii::$app->session->setFlash('error', 'Minimal 1 baris Detail Penilaian wajib diisi.');
            return false;
        }
        return true;
    }

    private function validateMasterAndDetails(MasterPenilaian $model, array $details): bool
    {
        $valid = $model->validate();
        $valid = Model::validateMultiple($details) && $valid;
        return $valid;
    }

    private function validateAnchorOwnership(array $details): bool
    {
        $valid = true;
        foreach ($details as $i => $detail) {
            if ($detail->id_kriteria && $detail->id_anchor) {
                $belongs = MasterAnchor::find()
                    ->where([
                        'id_anchor'   => (int)$detail->id_anchor,
                        'id_kriteria' => (int)$detail->id_kriteria,
                    ])->exists();
                if (!$belongs) {
                    // Kenapa: integritas data anchor↔kriteria
                    $detail->addError('id_anchor', 'Anchor tidak sesuai dengan kriteria pada baris #' . ($i + 1) . '.');
                    $valid = false;
                }
            }
        }
        return $valid;
    }

    private function saveWithTransaction(MasterPenilaian $model, array $details, array $deletedIDs): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->save(false)) {
                throw new \RuntimeException('Gagal menyimpan Master Penilaian.');
            }

            if (!empty($deletedIDs)) {
                DetailPenilaian::deleteAll(['id_detailpenilaian' => $deletedIDs]);
            }

            foreach ($details as $detail) {
                $detail->id_penilaian = $model->id_penilaian;
                if (!$detail->save(false)) {
                    throw new \RuntimeException('Gagal menyimpan Detail Penilaian.');
                }
            }

            if (method_exists($model, 'NilaiAkhir')) {
                $model->NilaiAkhir();
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            $model->addError('id_penilaian', 'Terjadi kesalahan saat menyimpan data.');
            Yii::$app->session->setFlash('error', 'Terjadi kesalahan saat menyimpan data.');
            return false;
        }
    }

    public function actionListAnchor($id_kriteria)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $anchors = MasterAnchor::find()
            ->where(['id_kriteria' => $id_kriteria])
            ->with('kriteria')
            ->asArray(false)
            ->all();

        return ArrayHelper::map($anchors, 'id_anchor', function ($model) {
            $bobot = $model->kriteria ? $model->kriteria->bobot : '-';
            return "{$model->level_anchor} - {$model->deskripsi} ({$model->nilai_anchor}) | Bobot: {$bobot}";
        });
    }

    public function actionListKriteria($id_departement)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($id_departement === '' || $id_departement === 'null') {
            $id_departement = null;
        }

        $query = MasterKriteria::find();

        if ($id_departement === null) {
            $query->where(['id_departement' => null]);
        } else {
            $query->where([
                'or',
                ['id_departement' => (int)$id_departement],
                ['id_departement' => null],
            ]);
        }

        $kriteria = $query
            ->orderBy(['id_departement' => SORT_ASC, 'nama_kriteria' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($kriteria, 'id_kriteria', function ($row) {
            $isUmum = $row['id_departement'] === null;
            return $row['nama_kriteria'] . ($isUmum ? ' [Umum]' : '');
        });
    }

    public function actionGetUserDepartement($id_user)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = User::findOne($id_user);
        if ($user) {
            return ['id_departement' => $user->id_departement];
        }
        return ['id_departement' => null];
    }
}
