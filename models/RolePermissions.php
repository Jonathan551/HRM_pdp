<?php
namespace app\models;

use yii\db\ActiveRecord;

class RolePermissions extends ActiveRecord
{
    public static function tableName() { return 'role_permissions'; }

    public static function primaryKey() { return ['id_jabatan','id_permission']; }

    public function rules()
    {
        return [
            [['id_jabatan','id_permission'], 'required'],
            [['id_jabatan','id_permission'], 'integer'],
            [['id_jabatan','id_permission'], 'unique', 'targetAttribute' => ['id_jabatan','id_permission']],
        ];
    }

    public function getPermission()
    {
        return $this->hasOne(Permissions::class, ['id_permission' => 'id_permission']);
    }
}
