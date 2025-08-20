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
            [['id_users', 'presentase_absensi', 'periode_awal', 'periode_akhir','catatan'], 'required'],
            [['id_kategori', 'nilai_akhir'], 'default', 'value' => null],
            [['id_users', 'id_kategori'], 'integer'], 
            [['nilai_akhir', 'presentase_absensi'], 'number'],
            [['periode_awal', 'periode_akhir'], 'safe'], 
            [['id_kategori'], 'safe'], 
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
            'id_kategori' => 'Status Nilai',
            'presentas_absensi' => 'Presentas Absensi',
            'catatan' => "Catatan",
        ];
    }

    /**
     * Gets query for [[DetailPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getBanding()
    {
        return $this->hasOne(BandingPenilaian::class, ['id_penilaian' => 'id_penilaian']);
    }

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

    public function getKategori()
    {
        return $this->hasOne(MasterKategori::class, ['id_kategori' => 'id_kategori']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id_users' => 'id_users']);
    }
    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    
    public function beforeSave($insert): bool
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
            
            if (!empty($this->id_kategori)) {
                $this->id_kategori = (int)$this->id_kategori;
            } else {
                $this->id_kategori = null;
            }

            if (!empty($this->nilai_akhir)) {
                $this->nilai_akhir = (float)$this->nilai_akhir;
            } else {
                $this->nilai_akhir = null;
            }
            
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if (!$insert || $this->hasDetailPenilaian()) {
            $this->NilaiAkhir();
        }
    }

    /**
     * Method untuk menghitung nilai akhir dan kategori
     * Dapat dipanggil secara manual dari controller
     */
    public function NilaiAkhir()
    {
        try {
            $totalBobot = 0;
            $totalNilaiBobot = 0;

            $details = $this->getDetailPenilaian()->with(['anchor', 'kriteria'])->all();

            if (empty($details)) {
                $this->updateAttributes([
                    'nilai_akhir' => 0,
                    'id_kategori' => null,
                ]);
                return true;
            }

            foreach ($details as $detail) {
                $nilai = $detail->anchor ? $detail->anchor->nilai_anchor : 0;
                $bobot = $detail->kriteria ? $detail->kriteria->bobot : 0;

                $totalNilaiBobot += $nilai * $bobot;
                $totalBobot += $bobot;

            }

            $nilaiAkhir = ($totalBobot > 0) ? $totalNilaiBobot / $totalBobot : 0;

            $kategori = MasterKategori::find()
                ->where(['<=', 'nilai_min', $nilaiAkhir])
                ->andWhere(['>=', 'nilai_max', $nilaiAkhir])
                ->one();
                
            $idKategori = $kategori ? (int)$kategori->id_kategori : null;

            $result = $this->updateAttributes([
                'nilai_akhir' => round($nilaiAkhir, 2), 
                'id_kategori' => $idKategori,
            ]);

            if (!$result) {
                return false;
            }

            $this->refresh();
            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if this penilaian has detail records
     */
    private function hasDetailPenilaian()
    {
        return $this->getDetailPenilaian()->exists();
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