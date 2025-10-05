<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Usersearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\MasterJabatan;
use yii\web\Response;
use app\models\MasterPenilaian;
use yii\filters\VerbFilter;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Usersearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id_users Id Users
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_users)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_users),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
   public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                $model->prefillFormValues();
                $this->handleFotoUpload($model, false);

                if ($model->validate() && $model->save(false)) {
                    return $this->redirect(['view', 'id_users' => $model->id_users]);
                }
            }
            return $this->render('create', ['model' => $model]);
        }
        $model->prefillFormValues();

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_users Id Users
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
   public function actionUpdate($id_users)
    {
        $model = $this->findModel($id_users);
        $model->scenario = 'update';

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                $model->prefillFormValues();
                $this->handleFotoUpload($model, true);

                if ($model->validate() && $model->save(false)) {
                    return $this->redirect(['view', 'id_users' => $model->id_users]);
                }
            }
            return $this->render('update', ['model' => $model]);
        }
        $model->prefillFormValues();

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_users Id Users
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_users)
    {
        $this->findModel($id_users)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_users Id Users
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_users)
    {
        if (($model = User::findOne(['id_users' => $id_users])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function handleFotoUpload(User $model, bool $isUpdate): void
    {
        $model->fotoFile = UploadedFile::getInstance($model, 'fotoFile');
        $oldName = $model->getOldAttribute('foto');

        if ($model->fotoFile && $model->validate(['fotoFile'])) {
            $this->ensureUploadDir();

            if ($isUpdate && $oldName && file_exists(Yii::getAlias('@webroot/uploads/users/' . $oldName))) {
                @unlink(Yii::getAlias('@webroot/uploads/users/' . $oldName));
            }

            $newName = Yii::$app->security->generateRandomString(16) . '.' . $model->fotoFile->extension;
            $path = Yii::getAlias('@webroot/uploads/users/' . $newName);

            $model->fotoFile->saveAs($path, false);
            $model->foto = $newName;
        } else {
            $model->foto = $oldName;
        }
    }

    protected function ensureUploadDir(): void
    {
        $dir = Yii::getAlias('@webroot/uploads/users');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    public function actionGetLevelJabatan(int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $jab = MasterJabatan::findOne($id);
        return ['level_jabatan' => $jab->level_jabatan ?? null];
    }

    public function actionLatestPenilaian(int $id_users): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $latest = MasterPenilaian::find()
            ->where(['id_users' => $id_users])
            ->orderBy(['periode_akhir' => SORT_DESC])
            ->one();

        return [
            'penilaian_terakhir' => $latest && $latest->periode_akhir
                ? Yii::$app->formatter->asDate($latest->periode_akhir, 'php:d-m-Y')
                : null,
        ];
    }
}
