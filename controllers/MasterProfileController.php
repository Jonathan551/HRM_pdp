<?php
namespace app\controllers;

use Yii;
use app\models\MasterProfile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class MasterProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], 
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = MasterProfile::find()->one();
        if (!$model) {
            throw new NotFoundHttpException('Profile belum diisi.');
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = MasterProfile::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Data profile tidak ditemukan");
        }

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_logo');
            if ($file) {
                $filename = 'logo_' . time() . '.' . $file->extension;
                $path = Yii::getAlias('@webroot/uploads/') . $filename;
                if ($file->saveAs($path)) {
                    $model->logo = $filename; 
                }
            }
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Profile berhasil diperbarui.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }
}
