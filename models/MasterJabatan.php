<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_jabatan".
 *
 * @property int $id_jabatan
 * @property string $nama_jabatan
 * @property int|null $level_jabatan
 * @property string|null $deskripsi
 *
 * @property Users[] $users
 */
class MasterJabatan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_jabatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level_jabatan', 'deskripsi'], 'default', 'value' => null],
            [['nama_jabatan'], 'required'],
            [['level_jabatan'], 'integer'],
            [['deskripsi'], 'string'],
            [['nama_jabatan'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jabatan' => 'Id Jabatan',
            'nama_jabatan' => 'Nama Jabatan',
            'level_jabatan' => 'Level Jabatan',
            'deskripsi' => 'Deskripsi',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id_jabatan' => 'id_jabatan']);
    }

}
