<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class ReportController extends Controller
{
    public function actionCetak($id)
    {
        try {
            $res = Yii::$app->reportService->buildPenilaianPdf((int)$id);
        } catch (\Throwable $e) {
            $msg = $e->getMessage();
            if ($e->getPrevious()) {
                $msg .= ' | Previous: ' . $e->getPrevious()->getMessage(); // Why: bantu debug akar masalah
            }
            throw new NotFoundHttpException($msg);
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        while (ob_get_level() > 0) { @ob_end_clean(); }

        return Yii::$app->response->sendFile(
            $res['path'],
            $res['filename'],
            ['mimeType' => 'application/pdf', 'inline' => false]
        )->on(Response::EVENT_AFTER_SEND, function() use ($res) { @unlink($res['path']); });
    }
}