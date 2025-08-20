<?php
namespace app\models;

use yii\db\ActiveRecord;

class Permissions extends ActiveRecord
{
    public static function tableName() { return 'permissions'; }

    public function rules()
    {
        return [
            [['nama_permission'], 'required'],
            [['deskripsi'], 'string'],
            [['nama_permission'], 'string', 'max' => 100],
            [['nama_permission'], 'unique'],
        ];
    }

    public function getRolePermissions()
    {
        return $this->hasMany(RolePermissions::class, ['id_permission' => 'id_permission']);
    }
}
