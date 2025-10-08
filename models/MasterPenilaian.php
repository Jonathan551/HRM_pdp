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
    public array $detailModels = [];

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
            [['id_users','presentase_absensi','periode_awal','periode_akhir','catatan'],
                'required','message'=>'{attribute} wajib diisi.'],

            [['catatan','nilai_akhir'], 'default', 'value'=>null],
            [['id_users','id_kategori'], 'integer'],
            [['nilai_akhir','presentase_absensi'], 'number'],
            [['periode_awal','periode_akhir'], 'validatePeriodeFormat'],
            ['periode_akhir', 'validatePeriodeOrder'],
            [['id_kategori'], 'safe'],
            [['id_users'], 'exist', 'skipOnError'=>true,
                'targetClass'=>User::class, 'targetAttribute'=>['id_users'=>'id_users']],

            [['detailModels'], 'validateDetails'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_penilaian' => 'Id Penilaian',
            'id_users' => 'Nama Karyawan',
            'nilai_akhir' => 'Nilai Akhir',
            'periode_awal' => 'Periode Awal',
            'periode_akhir' => 'Periode Akhir',
            'id_kategori' => 'Status Nilai',
            'presentas_absensi' => 'Presentas Absensi',
            'catatan' => "Catatan",
            'detailModels'        => Yii::t('app', 'Detail Penilaian'),
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

    public function validatePeriodeFormat($attribute)
    {
        $ts = $this->formathelper($this->$attribute);
        if ($this->$attribute !== null && $ts === null) {
            $this->addError($attribute, $this->getAttributeLabel($attribute).' tidak valid (gunakan dd-mm-YYYY).');
        }
    }

    public function validatePeriodeOrder($attribute)
    {
        $start = $this->formathelper($this->periode_awal);
        $end   = $this->formathelper($this->periode_akhir);


        if ($start === null || $end === null) return;

        if ($end < $start) {
            $this->addError('periode_akhir', 'Periode Akhir harus sama atau setelah Periode Awal.');
        } else {
            $this->periode_awal  = date('Y-m-d', $start);
            $this->periode_akhir = date('Y-m-d', $end);
        }
    }


    private function formathelper($val): ?int
    {
        if ($val === null || $val === '') return null;
        $val = trim((string)$val);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val)) {
            $dt = \DateTime::createFromFormat('Y-m-d', $val);
            return $dt ? $dt->getTimestamp() : null;
        }

        $dt = \DateTime::createFromFormat('d-m-Y', $val);
        if ($dt && $dt->format('d-m-Y') === $val) return $dt->getTimestamp();

        $dt = \DateTime::createFromFormat('d/m/Y', $val);
        if ($dt && $dt->format('d/m/Y') === $val) return $dt->getTimestamp();

        return null;
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

    public function validateDetails(string $attribute): void
    {
        if (empty($this->detailModels)) {
            $this->addError($attribute, Yii::t('app', 'Minimal satu baris Detail Penilaian harus diisi.'));
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        foreach (['periode_awal','periode_akhir'] as $attr) {
            $v = $this->$attr;
            if ($v && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
                $dt = \DateTime::createFromFormat('Y-m-d', $v);
                if ($dt) $this->$attr = $dt->format('d-m-Y');
            }
        }
    }
}