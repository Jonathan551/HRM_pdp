<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_departement".
 *
 * @property int $id_departement
 * @property string $nama_departement
 * @property string|null $deskripsi
 *
 * @property MasterKriteria[] $masterKriterias
 * @property Users[] $users
 */
class MasterDepartement extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_departement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'default', 'value' => null],
            [['nama_departement'], 'required'],
            [['deskripsi'], 'string'],
            [['nama_departement'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_departement' => 'Id Departement',
            'nama_departement' => 'Nama Departement',
            'deskripsi' => 'Deskripsi',
        ];
    }

    /**
     * Gets query for [[MasterKriterias]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getMasterKriterias()
    // {
    //     return $this->hasMany(MasterKriteria::class, ['id_departement' => 'id_departement']);
    // }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id_departement' => 'id_departement']);
    }

}
