<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\models\MasterAnchor;
use app\models\BatchAnchorInput;
use yii\data\ArrayDataProvider;
use app\models\MasterKriteria;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\BaseController;

/**
 * MasterAnchorController implements the CRUD actions for MasterAnchor model.
 */
class MasterAnchorController extends BaseController
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
        $rows = (new \yii\db\Query())
            ->select([
                'ma.id_kriteria',
                'mk.nama_kriteria',
                'd.nama_departement AS nama_departement',
                'COUNT(ma.id_anchor) AS jumlah_skala',
            ])
            ->from('master_anchor ma')
            ->innerJoin('master_kriteria mk', 'mk.id_kriteria = ma.id_kriteria')
            ->leftJoin('master_departement d', 'd.id_departement = mk.id_departement')
            ->groupBy([
                'ma.id_kriteria',
                'mk.nama_kriteria',
                'd.nama_departement',
            ])
            ->orderBy([
                'd.nama_departement' => SORT_ASC,
                'mk.nama_kriteria'  => SORT_ASC,
            ])
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $rows,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterAnchor model.
     * @param int $id_anchor Id Anchor
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionView($id_kriteria)
    {
        $kriteria = MasterKriteria::findOne($id_kriteria);
        if (!$kriteria) {
            throw new NotFoundHttpException('Kriteria tidak ditemukan.');
        }

        $anchors = MasterAnchor::find()
            ->where(['id_kriteria' => $id_kriteria])
            ->orderBy(['level_anchor' => SORT_ASC])
            ->all();

        return $this->render('view', [
            'kriteria' => $kriteria,
            'anchors'  => $anchors,
        ]);
    }

    /**
     * Creates a new MasterAnchor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BatchAnchorInput();

 
        $model->skala = $model->skala ?? 1;

        if ($model->load(Yii::$app->request->post())) {

       
            $model->anchors = Yii::$app->request->post('anchors', []);

            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($model->anchors as $row) {
                        $anchor = new MasterAnchor();
                        $anchor->id_kriteria   = $model->id_kriteria;
                        $anchor->level_anchor  = (int)$row['level_anchor'];
                        $anchor->deskripsi     = $row['deskripsi'];
                        $anchor->nilai_anchor  = $row['nilai_anchor'];

                        if (!$anchor->save()) {
                            throw new \Exception('Gagal simpan anchor level '.$row['level_anchor']);
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Anchor berhasil disimpan.');
                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }

        $departemenList = (new \yii\db\Query())
            ->select(['id_departement', 'nama_departement'])
            ->from('master_departement')
            ->orderBy(['nama_departement' => SORT_ASC])
            ->all();

        $departementDropdown = ArrayHelper::map($departemenList, 'id_departement', 'nama_departement');

        return $this->render('create', [
            'model' => $model,
            'departementDropdown' => $departementDropdown,
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
            return $this->redirect(['view', 'id_kriteria' => $model->id_kriteria]);
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
        $model = $this->findModel($id_anchor);

        $anchors = MasterAnchor::find()
            ->where(['id_kriteria' => $model->id_kriteria])
            ->orderBy(['level_anchor' => SORT_ASC])
            ->all();

        $maxLevel = 0;
        foreach ($anchors as $a) {
            if ($a->level_anchor > $maxLevel) {
                $maxLevel = $a->level_anchor;
            }
        }


        if ((int)$model->level_anchor !== (int)$maxLevel) {
            Yii::$app->session->setFlash(
                'error',
                'Level ini tidak boleh dihapus. Hapus dari level paling tinggi terlebih dahulu.'
            );
            return $this->redirect([
                'view',
                'id_kriteria' => $model->id_kriteria,
            ]);
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Level berhasil dihapus.');
        return $this->redirect([
            'view',
            'id_kriteria' => $model->id_kriteria,
        ]);
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

    public function actionKriteriaByDepartemen($id_departement)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $list = MasterKriteria::find()
            ->select(['id_kriteria', 'nama_kriteria'])
            ->where(['id_departement' => $id_departement])
            ->orderBy(['nama_kriteria' => SORT_ASC])
            ->asArray()
            ->all();

        return $list;
    }

    public function actionBulkUpdate($id_kriteria)
    {
        $kriteria = MasterKriteria::findOne($id_kriteria);
        if (!$kriteria) {
            throw new NotFoundHttpException('Kriteria tidak ditemukan.');
        }
        
        $existing = MasterAnchor::find()
            ->where(['id_kriteria' => $id_kriteria])
            ->orderBy(['level_anchor' => SORT_ASC])
            ->all();

        $prefill = [];
        $maxLevel = 0;
        foreach ($existing as $a) {
            $lvl = (int)$a->level_anchor;
            $maxLevel = max($maxLevel, $lvl);
            $prefill[$lvl] = [
                'level_anchor' => $lvl,
                'deskripsi'    => (string)$a->deskripsi,
                'nilai_anchor' => (string)$a->nilai_anchor,
            ];
        }
        
        $model = new BatchAnchorInput();
        $model->id_kriteria    = $id_kriteria;
        $model->id_departement = $kriteria->id_departement;   
        $model->skala          = $maxLevel ?: 1;
        $model->anchors        = $prefill;

        if ($model->load(Yii::$app->request->post())) {
            $model->id_departement = $kriteria->id_departement;
            
            $postBI    = Yii::$app->request->post('BatchAnchorInput', []);
            $anchorsIn = $postBI['anchors'] ?? [];
            $skala     = (int)$model->skala;

            $errorMsg = null;
            
            if (!is_array($anchorsIn) || count($anchorsIn) !== $skala) {
                $errorMsg = 'Jumlah level tidak sesuai skala. Expected: ' . $skala . ', Got: ' . count($anchorsIn);
            } else {
                $decimal = '/^\d+(\.\d{1,3})?$/';
                for ($i = 1; $i <= $skala; $i++) {
                    $row = $anchorsIn[$i] ?? null;
                    
                    if (!$row) {
                        $errorMsg = "Data untuk level {$i} tidak ditemukan.";
                        break;
                    }
                    
                    if (
                        !isset($row['deskripsi']) ||
                        !isset($row['nilai_anchor']) ||
                        !isset($row['level_anchor']) ||
                        trim($row['deskripsi']) === '' ||
                        $row['nilai_anchor'] === ''
                    ) {
                        $errorMsg = "Semua kolom deskripsi dan nilai anchor wajib diisi (level {$i}).";
                        break;
                    }

                    if ((int)$row['level_anchor'] !== $i) {
                        $errorMsg = 'Level tidak berurutan (harus 1..skala).';
                        break;
                    }
                    
                    if (!preg_match($decimal, (string)$row['nilai_anchor'])) {
                        $errorMsg = 'Nilai Anchor harus angka dengan maksimal 3 angka di belakang koma.';
                        break;
                    }
                    
                    if ((float)$row['nilai_anchor'] > $skala) {
                        $errorMsg = "Nilai Anchor level {$i} tidak boleh lebih besar dari skala ({$skala}).";
                        break;
                    }
                }
            }

            if ($errorMsg !== null) {
                Yii::$app->session->setFlash('error', $errorMsg);
            } else {
                $tx = Yii::$app->db->beginTransaction();
                try {
                    for ($i = 1; $i <= $skala; $i++) {
                        $row = $anchorsIn[$i];
                        
                        $anchor = MasterAnchor::findOne([
                            'id_kriteria'  => $id_kriteria,
                            'level_anchor' => $i,
                        ]);
                        
                        if (!$anchor) {
                            $anchor = new MasterAnchor();
                            $anchor->id_kriteria  = $id_kriteria;
                            $anchor->level_anchor = $i;
                        }

                        $anchor->deskripsi    = trim((string)$row['deskripsi']);
                        $anchor->nilai_anchor = (float)$row['nilai_anchor'];

                        if (!$anchor->save(false)) {
                            throw new \RuntimeException('Gagal menyimpan level ' . $i);
                        }
                    }
                    
                    MasterAnchor::deleteAll([
                        'and',
                        ['id_kriteria' => $id_kriteria],
                        ['>', 'level_anchor', $skala],
                    ]);

                    $tx->commit();
                    Yii::$app->session->setFlash('success', 'Master Anchor berhasil diperbarui.');
                    return $this->redirect(['view', 'id_kriteria' => $id_kriteria]);
                } catch (\Throwable $e) {
                    $tx->rollBack();
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
            }
        }
        
        return $this->render('bulk-update', [
            'kriteria' => $kriteria,
            'model'    => $model,
        ]);
    }
}

