<?php

namespace app\controllers;

use Yii;
use app\models\BandingPenilaian;
use app\models\BandingPenilaianSearch; // pastikan S besar
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Exception as DbException;
use app\controllers\BaseController;

class BandingPenilaianController extends BaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'review' => ['GET','POST'],
                ],
            ],
        ]);
    }

    private function isFinal(BandingPenilaian $m): bool
    {
        return $m->status !== BandingPenilaian::STATUS_REVIEW;
    }

    private function applyDecisionFromButton(BandingPenilaian $m, ?string $btn): void
    {
        if ($btn === 'terima') {
            $m->status = BandingPenilaian::STATUS_DITERIMA;
        } elseif ($btn === 'tolak') {
            $m->status = BandingPenilaian::STATUS_DITOLAK;
        }
    }

    private function trySave(BandingPenilaian $m): bool
    {
        try {
            if ($m->save()) {
                return true;
            }
            $first = '';
            foreach ($m->getFirstErrors() as $msg) { $first = $msg; break; }
            Yii::$app->session->setFlash('error', $first ?: 'Gagal menyimpan data.');
            return false;
        } catch (DbException $e) {
            Yii::$app->session->setFlash('error', 'Tidak dapat menyimpan: ' . $e->getMessage());
            return false;
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return false;
        }
    }

    private function handleForm(BandingPenilaian $model, string $viewName)
    {
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($this->trySave($model)) {
                Yii::$app->session->setFlash('success', 'Data tersimpan.');
                return $this->redirect(['view', 'id_banding' => $model->id_banding]);
            }
        }
        return $this->render($viewName, compact('model'));
    }

    private function redirectAfterDecision(BandingPenilaian $m, ?string $btn)
    {
        if ($btn === 'terima') {
            return $this->redirect(['master-penilaian/update', 'id_penilaian' => $m->id_penilaian]);
        }
        if ($btn === 'tolak') {
            return $this->redirect(['banding-penilaian/view', 'id_banding' => $m->id_banding]);
        }
        return $this->redirect(['view', 'id_banding' => $m->id_banding]);
    }

    public function actionIndex()
    {
        $searchModel  = new BandingPenilaianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', compact('searchModel','dataProvider'));
    }

    public function actionView($id_banding)
    {
        return $this->render('view', ['model' => $this->findModel($id_banding)]);
    }

    public function actionReview($id_banding)
    {
        $model = $this->findModel($id_banding);

        if ($this->isFinal($model)) {
            Yii::$app->session->setFlash('warning', 'Keputusan sudah final dan tidak dapat diubah.');
            return $this->redirect(['view', 'id_banding' => $model->id_banding]);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $btn = Yii::$app->request->post('submitBtn'); 
            $this->applyDecisionFromButton($model, $btn);

            if ($this->trySave($model)) {
                if ($btn === 'terima') {
                    Yii::$app->session->setFlash('success', 'Banding DITERIMA (final).');
                } elseif ($btn === 'tolak') {
                    Yii::$app->session->setFlash('success', 'Banding DITOLAK (final).');
                } else {
                    Yii::$app->session->setFlash('success', 'Draft review disimpan.');
                }
                return $this->redirectAfterDecision($model, $btn);
            }
        }

        return $this->render('review', compact('model'));
    }

    public function actionBanding($id_banding)
    {
        return $this->render('banding', ['model' => $this->findModel($id_banding)]);
    }
    public function actionUpdate($id_banding)
    {
        $model = $this->findModel($id_banding);

        if ($this->isFinal($model)) {
            Yii::$app->session->setFlash('warning', 'Data banding sudah final dan tidak dapat diubah.');
            return $this->redirect(['view', 'id_banding' => $model->id_banding]);
        }

        return $this->handleForm($model, 'update');
    }

    public function actionDelete($id_banding)
    {
        $this->findModel($id_banding)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id_banding)
    {
        if (($model = BandingPenilaian::findOne(['id_banding' => $id_banding])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
