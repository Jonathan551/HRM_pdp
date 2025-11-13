<?php
namespace app\controllers;

use Yii;
use app\controllers\BaseController;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\MasterPenilaian;
use app\models\MasterPenilaiansearch;
use app\models\DetailPenilaian;
use app\models\MasterKriteria;
use app\models\MasterAnchor;
use app\models\MasterPeriode;
use app\models\User;
use app\components\NotificationService;
use app\components\Model;

class MasterPenilaianController extends BaseController
{
    /** @inheritDoc */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => ['delete' => ['POST']],
                ],
            ]
        );
    }

    protected function readOnly(MasterPenilaian $m): bool
    {
        $p = $m->periode;
        if (!$p) return false;
        $today = new \DateTimeImmutable('today');
        $end   = \DateTimeImmutable::createFromFormat('Y-m-d', (string)$p->tanggal_selesai) ?: new \DateTimeImmutable($p->tanggal_selesai);
        return ($end < $today) || (strtolower((string)$p->status) === 'locked');
    }
    /**
     * List penilaian (filter via MasterPenilaiansearch).
     */
    public function actionIndex()
    {
        $searchModel  = new MasterPenilaiansearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $periodes = MasterPeriode::find()->orderBy(['tanggal_mulai' => SORT_DESC])->all();

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'periodes'     => $periodes,
        ]);
    }

    /**
     * @param int $id_penilaian
     */
    public function actionView($id_penilaian)
    {
        $model = $this->findModel($id_penilaian);
        return $this->render('view', ['model' => $model]);
    }

    /**
     * Modal view.
     * @param int $id_penilaian
     */
    public function actionViewModal($id_penilaian)
    {
        $this->layout = false;
        $model = $this->findModel($id_penilaian);
        return $this->render('view-modal', ['model' => $model]);
    }

    /** Penilaian dibuat otomatis oleh Periode. */
    public function actionCreate()
    {
        throw new \yii\web\ForbiddenHttpException('Penilaian dibuat otomatis dari Periode. Gunakan menu Periode.');
    }

    /**
     * Pengisian/ubah penilaian (STATE A/C).
     * @param int $id_penilaian
     */
    public function actionUpdate($id_penilaian)
    {
        /** @var MasterPenilaian $model */
        $model   = $this->findModel($id_penilaian);
        
        if ($this->readOnly($model)) {
            Yii::$app->session->setFlash('warning', 'Periode penilaian sudah berakhir. Data hanya bisa dilihat.');
            return $this->redirect(['view','id_penilaian'=>$model->id_penilaian]);
        }

        /** @var DetailPenilaian[] $details */
        $details = $model->detailPenilaian ?: [new DetailPenilaian()];

        if ($this->processForm($model, $details)) {
            NotificationService::fireUpdate($model, $model->id_users);
            Yii::$app->session->setFlash('success', 'Data berhasil diperbarui.');
            return $this->redirect(['view', 'id_penilaian' => $model->id_penilaian]);
        }

        return $this->render('update', [
            'model'        => $model,
            'detailModels' => $details,
        ]);
    }

    /**
     * Form khusus catatan & rekomendasi (STATE B).
     * @param int $id_penilaian
     */
    public function actionNotes($id_penilaian)
    {
        $model = $this->findModel($id_penilaian);

        if ($this->readOnly($model)) {
            Yii::$app->session->setFlash('warning', 'Periode penilaian sudah berakhir. Data hanya bisa dilihat.');
            return $this->redirect(['view','id_penilaian'=>$model->id_penilaian]);
        }

        if (!$this->hasAnyDetailOrScore($model)) {
            Yii::$app->session->setFlash('warning', 'Isi penilaian terlebih dahulu.');
            return $this->redirect(['update', 'id_penilaian' => $model->id_penilaian]);
        }
        if ($model->load(Yii::$app->request->post())) {
            $sendEmail = (bool)Yii::$app->request->post('send_email', 1);

            if ($model->save(false, ['catatan', 'rekomendasi', 'updated_at'])) {
                if ($sendEmail) {
                    $mail = $this->kirimEmailPenilaian($model); 
                    if ($mail['ok']) {
                        Yii::$app->session->addFlash('success', 'Catatan & Rekomendasi tersimpan. Email terkirim.');
                    } else {
                        Yii::$app->session->addFlash('warning', 'Catatan & Rekomendasi tersimpan. Email gagal: '.$mail['message']);
                    }
                } else {
                    Yii::$app->session->addFlash('success', 'Catatan & Rekomendasi tersimpan.');
                }
                return $this->redirect(['view', 'id_penilaian' => $model->id_penilaian]);
            }
            Yii::$app->session->setFlash('error', 'Gagal menyimpan Catatan & Rekomendasi.');
        }
        return $this->render('_note_form', ['model' => $model]);
    }

    /**
     * @param int $id_penilaian
     */
    public function actionDelete($id_penilaian)
    {
        $model = $this->findModel($id_penilaian);

        if ($this->readOnly($model)) {
            Yii::$app->session->setFlash('warning', 'Periode penilaian sudah berakhir. Data hanya bisa dilihat.');
            return $this->redirect(['view','id_penilaian'=>$model->id_penilaian]);
        }

        $targetUserId = $model->id_users;
        $pk           = (string) $model->getPrimaryKey();
        $kode         = $model->kode ?? $pk;

        $model->delete();

        NotificationService::fireDelete(
            $model,
            $targetUserId,
            'Laporan penilaian dihapus',
            "Laporan penilaian {$kode} telah dihapus.",
            $pk
        );

        Yii::$app->session->setFlash('success', 'Data berhasil dihapus.');
        return $this->redirect(['index']);
    }

    /**
     * @param int $id_penilaian
     * @return MasterPenilaian
     * @throws NotFoundHttpException
     */
    protected function findModel($id_penilaian)
    {
        $model = MasterPenilaian::findOne(['id_penilaian' => $id_penilaian]);
        if ($model !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function isFirstFill(MasterPenilaian $m): bool
    {
        return !$m->getDetailPenilaian()->exists() && $m->nilai_akhir === null;
    }

    /** Ada detail atau nilai akhir */
    protected function hasAnyDetailOrScore(MasterPenilaian $m): bool
    {
        return $m->getDetailPenilaian()->exists() || $m->nilai_akhir !== null;
    }

    /** Boleh tulis notes jika sudah ada detail/score tapi catatan & rekomendasi kosong (STATE B) */
    protected function canWriteNotes(MasterPenilaian $m): bool
    {
        return $this->hasAnyDetailOrScore($m) && empty($m->catatan) && empty($m->rekomendasi);
    }

    /** ==== Pipeline form (tetap) ==== */

    /**
     * @param MasterPenilaian $model
     * @param DetailPenilaian[] $details
     */
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

    /**
     * @param DetailPenilaian[] $existingDetails
     * @return DetailPenilaian[]
     */
    private function buildDetailsFromPost(array $existingDetails): array
    {
        $details = Model::createMultiple(DetailPenilaian::class, $existingDetails);
        Model::loadMultiple($details, Yii::$app->request->post());

        $details = array_values(array_filter(
            $details,
            static function (DetailPenilaian $d): bool {
                return !empty($d->id_kriteria) && !empty($d->id_anchor);
            }
        ));
        return array_values($details);
    }

    /**
     * @return int[]
     */
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
                        'id_anchor'   => (int) $detail->id_anchor,
                        'id_kriteria' => (int) $detail->id_kriteria,
                    ])->exists();
                if (!$belongs) {
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

    /** JSON endpoints */

    /**
     * @param int $id_kriteria
     * @return array<int,string>
     */
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

    /**
     * @param int|string|null $id_departement
     * @return array<int,string>
     */
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
            $query->where(['or',
                ['id_departement' => (int) $id_departement],
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

    /**
     * @param int $id_user
     * @return array{id_departement:int|null}
     */
    public function actionGetUserDepartement($id_user)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = User::findOne($id_user);
        if ($user) {
            return ['id_departement' => $user->id_departement];
        }
        return ['id_departement' => null];
    }

    /**
     * Kirim email hasil penilaian (tetap).
     * @return array{ok:bool,message:string}
     */
    protected function kirimEmailPenilaian(MasterPenilaian $model): array
    {
        $toEmail = $model->user->email ?? null;
        if (!filter_var((string) $toEmail, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'message' => 'Email tujuan tidak valid/tersedia pada users'];
        }

        try {
            $pdf = Yii::$app->reportService->buildPenilaianPdf((int) $model->id_penilaian);
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Gagal membangun PDF: ' . $e->getMessage()];
        }

        $periode = $model->periode;
        $periodeNama  = $periode->nama ?? 'Periode';
        $periodeRange = ($periode->tanggal_mulai ?? '') . ' - ' . ($periode->tanggal_selesai ?? '');

        $subject = sprintf('Hasil Penilaian #%d', (int) $model->id_penilaian);
        $body = sprintf(
            '<p>Halo %s,</p><p>Terlampir hasil penilaian periode <strong>%s</strong> (%s) dengan skor akhir <strong>%s</strong>.</p><p>Terima kasih,<br>%s</p>',
            htmlspecialchars($model->user->nama ?? 'Karyawan', ENT_QUOTES),
            htmlspecialchars($periodeNama, ENT_QUOTES),
            htmlspecialchars($periodeRange, ENT_QUOTES),
            htmlspecialchars((string) $model->nilai_akhir, ENT_QUOTES),
            htmlspecialchars(Yii::$app->name, ENT_QUOTES)
        );

        try {
            $ok = Yii::$app->phpMailer->send(
                $toEmail,
                $subject,
                $body,
                [$pdf['path']],
                Yii::$app->name
            );
            @unlink($pdf['path']);

            return ['ok' => (bool) $ok, 'message' => $ok ? 'Terkirim' : 'Mailer gagal'];
        } catch (\Throwable $e) {
            @unlink($pdf['path']);
            return ['ok' => false, 'message' => 'Gagal kirim email: ' . $e->getMessage()];
        }
    }
}
