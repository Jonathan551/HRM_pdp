<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_penilaian".
 *
 * @property int $id_penilaian
 * @property int|null $id_users
 * @property float|null $nilai_akhir
 * @property string|null $periode_awal
 * @property string|null $periode_akhir
 * @property string|null $status
 * @property string|null $presentas_absensi
 *
 
 * @property DetailPenilaian[] $detailPenilaians
 * @property User $users
 */
class MasterPenilaian extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_penilaian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_users', 'nilai_akhir', 'periode_awal', 'periode_akhir', 'status', 'presentase_absensi'], 'default', 'value' => null],
            [['id_users'], 'integer'],
            [['nilai_akhir'], 'number'],
            [['periode_awal', 'periode_akhir'], 'safe'],
            [['status', 'presentase_absensi'], 'string', 'max' => 50],
            [['id_users'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_users' => 'id_users']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_penilaian' => 'Id Penilaian',
            'id_users' => 'Id Users',
            'nilai_akhir' => 'Nilai Akhir',
            'periode_awal' => 'Periode Awal',
            'periode_akhir' => 'Periode Akhir',
            'status' => 'Status',
            'presentas_absensi' => 'Presentas Absensi',
        ];
    }

    /**
     * Gets query for [[BandingPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getBandingPenilaians()
    // {
    //     return $this->hasMany(BandingPenilaian::class, ['id_penilaian' => 'id_penilaian']);
    // }

    /**
     * Gets query for [[DetailPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    
    public function getDetailPenilaian()
    {
        return $this->hasMany(DetailPenilaian::class, ['id_penilaian' => 'id_penilaian']);
    }

    public function getAnchor()
    {
        return $this->hasOne(MasterAnchor::class, ['id_anchor' => 'id_anchor']);
    }

    public function getKriteria()
    {
        return $this->hasOne(MasterKriteria::class, ['id_kriteria' => 'id_kriteria']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id_users' => 'id_users']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            foreach (['periode_awal', 'periode_akhir'] as $attr) {
                if (!empty($this->$attr) && preg_match('/\d{2}-\d{2}-\d{4}/', $this->$attr)) {
                    if (strpos($this->$attr, ':') !== false) {
                        $this->$attr = Yii::$app->formatter->asDatetime($this->$attr, 'php:Y-m-d H:i:s');
                    } else {
                        $this->$attr = Yii::$app->formatter->asDate($this->$attr, 'php:Y-m-d');
                    }
                }
            }
            if (!$this->isRelationPopulated('detailPenilaian')) {
                $this->populateRelation('detailPenilaian', $this->getDetailPenilaian()->with(['anchor', 'kriteria'])->all());
            }

            $totalBobot = 0;
            $totalNilaiBobot = 0;

            foreach ($this->detailPenilaian as $detail) {
                $nilai = $detail->anchor ? $detail->anchor->nilai_anchor : 0;
                $bobot = $detail->kriteria ? $detail->kriteria->bobot : 0;

                $totalNilaiBobot += $nilai * $bobot;
                $totalBobot += $bobot;
            }

            $this->nilai_akhir = ($totalBobot > 0) ? $totalNilaiBobot / $totalBobot : 0;

            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();

        foreach (['periode_awal', 'periode_akhir'] as $attr) {
            if (!empty($this->$attr) && $this->$attr != '0000-00-00' && $this->$attr != '0000-00-00 00:00:00') {
                if (strpos($this->$attr, ':') !== false) {
                    $this->$attr = Yii::$app->formatter->asDatetime($this->$attr, 'php:d-m-Y H:i');
                } else {
                    $this->$attr = Yii::$app->formatter->asDate($this->$attr, 'php:d-m-Y');
                }
            }
        }
    }

}
