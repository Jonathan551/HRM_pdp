<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detail_penilaian".
 *
 * @property int $id_detailpenilaian
 * @property int|null $id_penilaian
 * @property int|null $id_kriteria
 * @property int|null $id_anchor
 *
 * @property MasterAnchor $anchor
 * @property MasterKriteria $kriteria
 * @property MasterPenilaian $penilaian
 */
class DetailPenilaian extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_penilaian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_penilaian', 'id_kriteria', 'id_anchor'], 'default', 'value' => null],
            [['id_penilaian', 'id_kriteria', 'id_anchor'], 'integer'],
            [['id_penilaian'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPenilaian::class, 'targetAttribute' => ['id_penilaian' => 'id_penilaian']],
            [['id_kriteria'], 'exist', 'skipOnError' => true, 'targetClass' => MasterKriteria::class, 'targetAttribute' => ['id_kriteria' => 'id_kriteria']],
            [['id_anchor'], 'exist', 'skipOnError' => true, 'targetClass' => MasterAnchor::class, 'targetAttribute' => ['id_anchor' => 'id_anchor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_detailpenilaian' => 'Id Detailpenilaian',
            'id_penilaian' => 'Id Penilaian',
            'id_kriteria' => 'Id Kriteria',
            'id_anchor' => 'Id Anchor',
        ];
    }

    /**
     * Gets query for [[Anchor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnchor()
    {
        return $this->hasOne(MasterAnchor::class, ['id_anchor' => 'id_anchor']);
    }

    /**
     * Gets query for [[Kriteria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKriteria()
    {
        return $this->hasOne(MasterKriteria::class, ['id_kriteria' => 'id_kriteria']);
    }
    /**
     * Gets query for [[Penilaian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenilaian()
    {
        return $this->hasOne(MasterPenilaian::class, ['id_penilaian' => 'id_penilaian']);
    }

}
