<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_anchor".
 *
 * @property int $id_anchor
 * @property int|null $id_kriteria
 * @property int|null $level_anchor
 * @property string|null $deskripsi
 * @property int|null $nilai_anchor
 *
 * @property DetailPenilaian[] $detailPenilaians
 * @property MasterKriteria $kriteria
 */
class MasterAnchor extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_anchor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kriteria', 'level_anchor', 'deskripsi', 'nilai_anchor'], 'default', 'value' => null],
            [['id_kriteria', 'level_anchor', 'nilai_anchor'], 'integer'],
            [['deskripsi'], 'string'],
            [['id_kriteria'], 'exist', 'skipOnError' => true, 'targetClass' => MasterKriteria::class, 'targetAttribute' => ['id_kriteria' => 'id_kriteria']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_anchor' => 'Id Anchor',
            'id_kriteria' => 'Id Kriteria',
            'level_anchor' => 'Level Anchor',
            'deskripsi' => 'Deskripsi',
            'nilai_anchor' => 'Nilai Anchor',
        ];
    }

    /**
     * Gets query for [[DetailPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getDetailPenilaians()
    // {
    //     return $this->hasMany(DetailPenilaian::class, ['id_anchor' => 'id_anchor']);
    // }

    /**
     * Gets query for [[Kriteria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKriteria()
    {
        return $this->hasOne(MasterKriteria::class, ['id_kriteria' => 'id_kriteria']);
    }

}
