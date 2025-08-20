<?php
namespace app\components;

use Yii;
use yii\db\Query;

class UserAccess
{

    private const AUTO_REGISTER = true;

    public static function hasPermission(string $permission, ?int $userId = null)
    {
        $userId = $userId ?? Yii::$app->user->identity->id_users ?? null;
        if (!$userId) return false;

        if (self::AUTO_REGISTER) {
            $exists = (new Query())->from('permissions')
                ->where(['nama_permission' => $permission])->exists();
            if (!$exists) {
                Yii::$app->db->createCommand()->insert('permissions', [
                    'nama_permission' => $permission,
                    'deskripsi'       => 'Auto generated',
                ])->execute();
            }
        }

        $cacheKey = "perm_user_{$userId}";
        $userPerms = Yii::$app->cache->get($cacheKey);

        if ($userPerms === false) {
            $userPerms = (new Query())
                ->select('p.nama_permission')
                ->from(['u' => 'users'])
                ->innerJoin(['rp' => 'role_permissions'], 'u.id_jabatan = rp.id_jabatan')
                ->innerJoin(['p'  => 'permissions'], 'rp.id_permission = p.id_permission')
                ->where(['u.id_users' => $userId])
                ->column();

            Yii::$app->cache->set($cacheKey, $userPerms, 300); 
        }

        return in_array($permission, $userPerms, true);
    }

    public static function invalidateUserCache(?int $userId = null)
    {
        if ($userId) {
            Yii::$app->cache->delete("perm_user_{$userId}");
        } else {
            Yii::$app->cache->flush();
        }
    }
}
