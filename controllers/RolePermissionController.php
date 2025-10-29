<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\db\Query;
use app\components\UserAccess;
use app\models\MasterJabatan;
use app\models\Permissions;
use app\controllers\BaseController;

class RolePermissionController extends BaseController
{
    public function actionIndex($id_jabatan = null)
    {

        $jabatans = MasterJabatan::find()->orderBy(['nama_jabatan'=>SORT_ASC])->all();
        $permissions = Permissions::find()->orderBy(['nama_permission'=>SORT_ASC])->all();
        $assigned = [];

        if ($id_jabatan) {
            $assigned = (new Query())
                ->select('id_permission')
                ->from('role_permissions')
                ->where(['id_jabatan' => $id_jabatan])
                ->column();
        }

        if (Yii::$app->request->isPost && $id_jabatan) {
            $checked = Yii::$app->request->post('permissions', []);

            Yii::$app->db->createCommand()->delete('role_permissions', ['id_jabatan' => $id_jabatan])->execute();
            foreach ($checked as $idPermission) {
                Yii::$app->db->createCommand()->insert('role_permissions', [
                    'id_jabatan'   => (int)$id_jabatan,
                    'id_permission'=> (int)$idPermission
                ])->execute();
            }

            Yii::$app->session->setFlash('success', 'Permissions berhasil diperbarui.');
            return $this->redirect(['index', 'id_jabatan' => $id_jabatan]);
        }

        return $this->render('index', compact('jabatans','permissions','assigned','id_jabatan'));
    }
}
