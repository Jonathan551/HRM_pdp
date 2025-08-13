<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function beforeAction($action): bool|Response
    {
        $session = Yii::$app->session;

        if (in_array($action->id, ['login', 'error'])) {
            return parent::beforeAction($action);
        }

        if (!$session->has('username')) {
            return $this->redirect(['site/login']);
        }

        return parent::beforeAction($action);
    }

}
