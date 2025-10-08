<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\components\NotificationService;

class NotificationController extends Controller
{
    // GET /index.php?r=notification/poll
    public function actionPoll(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $uid = (int)Yii::$app->user->id;
        if ($uid <= 0) return ['unread'=>0, 'items'=>[]];

        return [
            'unread' => NotificationService::unreadCount($uid),
            'items'  => NotificationService::latest($uid, 5),
        ];
    }

    public function actionIndex()
    {
        $uid = (int)Yii::$app->user->id;
        $items = NotificationService::latest($uid, 50);
        return $this->render('index', ['items'=>$items]);
    }

    public function actionReadAll()
    {
        $uid = (int)Yii::$app->user->id;
        NotificationService::markReadAll($uid);
        return $this->redirect(['index']);
    }

    public function actionRead($id)
    {
        $uid = (int)Yii::$app->user->id;
        NotificationService::markRead((int)$id, $uid);
        return $this->redirect(['index']);
    }
}
