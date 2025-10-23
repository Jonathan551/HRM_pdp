<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\BandingPenilaian;
use app\models\MasterEvent;
use app\models\User;
use app\models\MasterKriteria;
use app\models\MasterPenilaian;
use app\models\MasterKategori;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;


class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $kriteriaCount = (int) MasterKriteria::find()->count();
        $userCount     = (int) User::find()->count();
        $bandingCount  = (int) BandingPenilaian::find()->count();
        $eventCount    = (int) MasterEvent::find()->count();
        $latestPenilaian = MasterPenilaian::find()
            ->orderBy(['id_penilaian' => SORT_DESC]) 
            ->limit(5)
            ->all();
        $latestBanding = BandingPenilaian::find()
            ->orderBy(['id_banding' => SORT_DESC]) 
            ->limit(5)
            ->all();
        $avgNilai = (float) (MasterPenilaian::find()->average('nilai_akhir') ?? 0);

        $dataKategori = [];
            foreach (MasterKategori::find()->orderBy(['nilai_min' => SORT_ASC])->all() as $kat) {
                $jumlah = (int) MasterPenilaian::find()
                    ->where(['between', 'nilai_akhir', $kat->nilai_min, $kat->nilai_max])
                    ->count();
                $dataKategori[] = ['nama' => $kat->nama_kategori, 'jumlah' => $jumlah];
        }

        return $this->render('index', [
            'kriteriaCount' => $kriteriaCount,
            'userCount'     => $userCount,
            'bandingCount'  => $bandingCount,
            'eventCount'    => $eventCount,
            'avgNilai'      => $avgNilai,       
            'dataKategori'  => $dataKategori,
            'latestPenilaian'=> $latestPenilaian,
            'latestBanding'  => $latestBanding,   
        ]);
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'guest-main';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name'     => 'was_logged_out',
                    'value'    => '',
                    'expire'   => time() - 3600,
                    'httpOnly' => false,
                    'sameSite' => 'Lax',
                    'path'     => '/',
                    'secure'   => false,
                ]));
                Yii::$app->session->setFlash('success', 'Login berhasil, selamat datang!');
                return $this->redirect(['site/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Login gagal: username atau password salah!');
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


   public function actionLogout()
    {
        Yii::$app->user->logout(true);

        $s = Yii::$app->session;
        if ($s->isActive) $s->destroy();
        $s->open();
        $s->regenerateID(true);


        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name'     => 'was_logged_out',
            'value'    => '1',
            'expire'   => time() + 600, 
            'httpOnly' => false,
            'sameSite' => 'Lax',
            'path'     => '/',         
            'secure'   => false,        
        ]));

        return $this->redirect(['site/login']);
    }
}
