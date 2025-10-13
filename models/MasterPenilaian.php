<?php
// FILE: models/MasterPenilaian.php
namespace app\models;

use Yii;

class MasterPenilaian extends \yii\db\ActiveRecord
{
    public array $detailModels = [];

    /** Set true jika kolom di DB adalah DATETIME/TIMESTAMP */
    private bool $storePeriodeAsDateTime;

    private const APP_TZ = 'Asia/Jakarta';

    public static function tableName() { return 'master_penilaian'; }

    public function init(): void
    {
        parent::init();
        $schema = Yii::$app->db->schema->getTableSchema(static::tableName(), true);
        $tAwal  = $schema->columns['periode_awal']->type  ?? null;
        $tAkhir = $schema->columns['periode_akhir']->type ?? null;
        $this->storePeriodeAsDateTime = in_array($tAwal, ['datetime','timestamp'], true)
                                     || in_array($tAkhir, ['datetime','timestamp'], true);
    }

    public function rules()
    {
        return [
            [['id_users','presentase_absensi','periode_awal','periode_akhir','catatan'], 'required','message'=>'{attribute} wajib diisi.'],
            [['catatan','nilai_akhir'], 'default', 'value'=>null],
            [['id_users','id_kategori'], 'integer'],
            [['nilai_akhir','presentase_absensi'], 'number'],
            [['periode_awal','periode_akhir'], 'validatePeriodeFormat'],
            ['periode_akhir', 'validatePeriodeOrder'],
            [['id_kategori'], 'safe'],
            [['id_users'], 'exist', 'skipOnError'=>true,'targetClass'=>User::class,'targetAttribute'=>['id_users'=>'id_users']],
            [['detailModels'], 'validateDetails'],
        ];
    }

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
            'detailModels' => Yii::t('app', 'Detail Penilaian'),
        ];
    }

    public function getDetailPenilaian(){ return $this->hasMany(DetailPenilaian::class, ['id_penilaian'=>'id_penilaian']); }
    public function getKategori(){ return $this->hasOne(MasterKategori::class, ['id_kategori'=>'id_kategori']); }
    public function getUser(){ return $this->hasOne(User::class, ['id_users'=>'id_users']); }

   
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) return false;

        $this->id_kategori = $this->id_kategori !== null && $this->id_kategori !== '' ? (int)$this->id_kategori : null;
        $this->nilai_akhir = ($this->nilai_akhir === '' || $this->nilai_akhir === null) ? null : (float)$this->nilai_akhir;

        foreach (['periode_awal','periode_akhir'] as $attr) {
            if (!empty($this->$attr)) {
                $dt = $this->parseDate($this->$attr);
                if ($dt) $this->$attr = $this->formatForDb($dt); 
            }
        }
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert || $this->hasDetailPenilaian()) {
            $this->NilaiAkhir();
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        foreach (['periode_awal','periode_akhir'] as $attr) {
            $v = $this->$attr;
            if (!$v) continue;
            if (preg_match('/^\d{4}-\d{2}-\d{2}(?:\s+\d{2}:\d{2}:\d{2})?$/', $v)) {
                $fmt = strlen($v) > 10 ? '!Y-m-d H:i:s' : '!Y-m-d';
                $dt  = \DateTimeImmutable::createFromFormat($fmt, $v, new \DateTimeZone(self::APP_TZ));
                if ($dt) { $this->$attr = $dt->format('d-m-Y'); }
            }
        }
    }


    public function validatePeriodeFormat($attribute): void
    {
        if ($this->$attribute === null || $this->$attribute === '') return;
        if (!$this->parseDate($this->$attribute)) {
            $this->addError($attribute, $this->getAttributeLabel($attribute).' tidak valid (format dd-mm-YYYY / dd/mm/YYYY).');
        }
    }

    public function validatePeriodeOrder($attribute): void
    {
        $start = $this->parseDate($this->periode_awal);
        $end   = $this->parseDate($this->periode_akhir);
        if (!$start || !$end) return;

        if ($end < $start) {
            $this->addError('periode_akhir', 'Periode Akhir harus sama atau setelah Periode Awal.');
            return;
        }

        $this->periode_awal  = $this->formatForDb($start);
        $this->periode_akhir = $this->formatForDb($end);
    }

    private function normalizeDateString(?string $val): ?string
    {
        if ($val === null) return null;
        $v = trim($val);
        if ($v === '') return null;
        $v = preg_replace('/\s+/u', '', $v);
        $v = str_replace(['–','—','.'], ['-','-','-'], $v);
        return $v;
    }

    private function parseDate($val): ?\DateTimeImmutable
    {
        $v = $this->normalizeDateString(is_string($val) ? $val : null);
        if ($v === null) return null;
        $tz = new \DateTimeZone(self::APP_TZ);

        $formats = ['!Y-m-d','!d-m-Y','!j-n-Y','!d/m/Y','!j/n/Y'];
        foreach ($formats as $fmt) {
            $dt = \DateTimeImmutable::createFromFormat($fmt, $v, $tz);
            if ($dt) {
                $err = \DateTimeImmutable::getLastErrors();
                if (empty($err['warning_count']) && empty($err['error_count'])) {
                    return $dt;
                }
            }
        }
        return null;
    }

    private function formatForDb(\DateTimeImmutable $dt): string
    {
        if ($this->storePeriodeAsDateTime) {
            $dtNoon = $dt->setTime(12, 0, 0); 
            return $dtNoon->format('Y-m-d H:i:s');
        }
        return $dt->format('Y-m-d'); // kolom DATE
    }

    public function NilaiAkhir()
    {
        try {
            $totalBobot = 0.0; $totalNilaiBobot = 0.0;
            $details = $this->getDetailPenilaian()->with(['anchor','kriteria'])->all();
            if (empty($details)) {
                $this->updateAttributes(['nilai_akhir'=>0,'id_kategori'=>null]);
                return true;
            }
            foreach ($details as $d) {
                $nilai = $d->anchor->nilai_anchor ?? 0;
                $bobot = $d->kriteria->bobot ?? 0;
                $totalNilaiBobot += $nilai * $bobot;
                $totalBobot      += $bobot;
            }
            $nilaiAkhir = $totalBobot > 0 ? $totalNilaiBobot / $totalBobot : 0;
            $kategori = MasterKategori::find()
                ->where(['<=','nilai_min',$nilaiAkhir])
                ->andWhere(['>=','nilai_max',$nilaiAkhir])
                ->one();
            $this->updateAttributes([
                'nilai_akhir' => round($nilaiAkhir, 2),
                'id_kategori' => $kategori ? (int)$kategori->id_kategori : null,
            ]);
            $this->refresh();
            return true;
        } catch (\Throwable $e) {
            Yii::error('Hitung NilaiAkhir gagal: '.$e->getMessage(), __METHOD__);
            return false;
        }
    }

    private function hasDetailPenilaian(){ return $this->getDetailPenilaian()->exists(); }

    public function validateDetails(string $attribute): void
    {
        if (empty($this->detailModels)) {
            $this->addError($attribute, Yii::t('app','Minimal satu baris Detail Penilaian harus diisi.'));
        }
    }
}
