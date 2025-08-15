<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_kriteria".
 *
 * @property int $id_kriteria
 * @property int|null $id_departement
 * @property string|null $nama_kriteria
 * @property string|null $deskripsi
 * @property int|null $bobot
 *
 * @property MasterDepartement $departement
 * @property DetailPenilaian[] $detailPenilaians
 * @property MasterAnchor[] $masterAnchors
 */
class MasterKriteria extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kriteria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_departement', 'nama_kriteria', 'deskripsi', 'bobot'], 'default', 'value' => null],
            [['id_departement', 'bobot'], 'integer'],
            [['deskripsi'], 'string'],
            [['nama_kriteria'], 'string', 'max' => 100],
            [['id_departement'], 'exist', 'skipOnError' => true, 'targetClass' => MasterDepartement::class, 'targetAttribute' => ['id_departement' => 'id_departement']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_kriteria' => 'Id Kriteria',
            'id_departement' => 'Id Departement',
            'nama_kriteria' => 'Nama Kriteria',
            'deskripsi' => 'Deskripsi',
            'bobot' => 'Bobot',
        ];
    }

    /**
     * Gets query for [[Departement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartement()
    {
        return $this->hasOne(MasterDepartement::class, ['id_departement' => 'id_departement']);
    }

    /**
     * Gets query for [[DetailPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getDetailPenilaians()
    // {
    //     return $this->hasMany(DetailPenilaian::class, ['id_kriteria' => 'id_kriteria']);
    // }

    /**
     * Gets query for [[MasterAnchors]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getMasterAnchors()
    // {
    //     return $this->hasMany(MasterAnchor::class, ['id_kriteria' => 'id_kriteria']);
    // }

}
