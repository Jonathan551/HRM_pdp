<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\components\UserAccess;

class BaseController extends Controller
{
    protected array $accessMap = [];

    public function beforeAction($action): bool|Response
    {
        
        if (in_array($action->id, ['login', 'error'])) {
            return parent::beforeAction($action);
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $route = $this->id . '/' . $action->id; 
        if (!UserAccess::hasPermission($route)) {
            Yii::$app->session->setFlash('error', 'Anda tidak punya akses ke halaman ini.');
            return $this->redirect(['site/error']);
        }

        return parent::beforeAction($action);
    }
}
