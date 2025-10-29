<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\UserAccess;

class BaseController extends Controller
{
    protected array $accessMap = [];

    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        
        if (in_array($action->id, ['login', 'error'])) {
            return true;
        }

        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Silakan login terlebih dahulu.');
            Yii::$app->response->redirect(['site/login'])->send();
            Yii::$app->end();
        }

        $route = 'akses_' . $this->id;
        
        Yii::info("Checking permission: {$route} for user: " . Yii::$app->user->id, __METHOD__);
        
        if (!UserAccess::hasPermission($route)) {
            Yii::$app->session->setFlash('error', 'Anda tidak punya akses ke halaman ini.');
            Yii::$app->response->redirect(['site/error'])->send();
            Yii::$app->end();
        }
        return true;
    }
}