<?php
namespace app\components;

use Yii;
use yii\db\Query;

class UserAccess
{
    private const AUTO_REGISTER = true;
    private static $permCache = [];

    public static function hasPermission(string $permission, ?int $userId = null)
    {
        $userId = $userId ?? Yii::$app->user->identity->id_users ?? null;
        if (!$userId) return false;

        if (self::AUTO_REGISTER) {
            $exists = (new Query())->from('permissions')
                ->where(['nama_permission' => $permission])->exists();
            
            if (!$exists) {
                try {
                    Yii::$app->db->createCommand()->insert('permissions', [
                        'nama_permission' => $permission,
                        'deskripsi'       => 'Auto generated',
                    ])->execute();
                } catch (\Exception $e) {
                    Yii::error("Failed to auto-register permission: {$permission}", __METHOD__);
                }
            }
        }

        if (!isset(self::$permCache[$userId])) {
            self::$permCache[$userId] = (new Query())
                ->select('p.nama_permission')
                ->from(['u' => 'users'])
                ->innerJoin(['rp' => 'role_permissions'], 'u.id_jabatan = rp.id_jabatan')
                ->innerJoin(['p'  => 'permissions'], 'rp.id_permission = p.id_permission')
                ->where(['u.id_users' => $userId])
                ->column();
        }

        return in_array($permission, self::$permCache[$userId], true);
    }
}