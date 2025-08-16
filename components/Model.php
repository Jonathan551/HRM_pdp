<?php
namespace app\components;

use yii\base\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * Membuat multiple model instances berdasarkan post data
     */
   public static function createMultiple($modelClass, $multipleModels = [])
    {
        $post = \Yii::$app->request->post();
        $formName = (new $modelClass)->formName();
        $models = [];

        $oldModels = [];
        if (!empty($multipleModels)) {
            foreach ($multipleModels as $model) {
                if ($model->id_detailpenilaian !== null) {
                    $oldModels[$model->id_detailpenilaian] = $model;
                }
            }
        }

        if (isset($post[$formName]) && is_array($post[$formName])) {
            foreach ($post[$formName] as $i => $item) {
                if (isset($item['id_detailpenilaian']) && isset($oldModels[$item['id_detailpenilaian']])) {
                    $models[] = $oldModels[$item['id_detailpenilaian']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        return $models;
    }
}
