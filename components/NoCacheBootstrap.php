<?php
namespace app\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Response;

class NoCacheBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if (function_exists('session_cache_limiter')) {
            @session_cache_limiter('nocache');
        }

        $app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $res = $event->sender;
            $ct  = $res->headers->get('Content-Type', '');
            $isHtml = ($ct === '') || stripos($ct, 'text/html') === 0;

            if (!$isHtml || Yii::$app->request->isAjax) return;

            $h = $res->headers;
            $h->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $h->add('Cache-Control', 'post-check=0, pre-check=0');
            $h->set('Pragma', 'no-cache');
            $h->set('Expires', 'Thu, 19 Nov 1981 08:52:00 GMT');
        });
    }
}
