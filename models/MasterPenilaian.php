<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class MasterPenilaian extends \yii\db\ActiveRecord
{
    public array $detailModels = [];
    public bool $storePeriodeAsDateTime = false;

    public static function tableName() { return 'master_penilaian'; }

     public function behaviors(): array
    {
        return [
            TimestampBehavior::class, 
        ];
    }
    
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
            [['id_users','presentase_absensi','id_periode'], 'required','message'=>'{attribute} wajib diisi.'],
            [['catatan','nilai_akhir',"rekomendasi"], 'default', 'value'=>null],
            [['id_users','id_kategori','id_periode'], 'integer'],
            [['nilai_akhir','presentase_absensi'], 'number'],
            [['id_kategori'], 'safe'],
            [['id_users'], 'exist', 'skipOnError'=>true,'targetClass'=>User::class,'targetAttribute'=>['id_users'=>'id_users']],
            [['detailModels'], 'validateDetails'],
            [['storePeriodeAsDateTime'], 'boolean'],
            [['storePeriodeAsDateTime'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_penilaian' => 'Id Penilaian',
            'id_users' => 'Nama Karyawan',
            'id_periode'  => 'Periode',
            'id_kategori' => 'Status Nilai',
            'nilai_akhir' => 'Nilai Akhir',
            'presentas_absensi' => 'Presentas Absensi',
            'rekomendasi' => 'Rekomendasi',
            'catatan' => "Catatan",
            'detailModels' => Yii::t('app', 'Detail Penilaian'),
        ];
    }

    public function getPeriode() { return $this->hasOne(MasterPeriode::class, ['id_periode' => 'id_periode']); }
    public function getDetailPenilaian(){ return $this->hasMany(DetailPenilaian::class, ['id_penilaian'=>'id_penilaian']); }
    public function getKategori(){ return $this->hasOne(MasterKategori::class, ['id_kategori'=>'id_kategori']); }
    public function getUser(){ return $this->hasOne(User::class, ['id_users'=>'id_users']); }
    public function getBanding(){ return $this->hasOne(BandingPenilaian::class, ['id_penilaian' => 'id_penilaian'])
            ->inverseOf('penilaian'); }
    
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert || $this->hasDetailPenilaian()) {
            $this->NilaiAkhir();
        }
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
