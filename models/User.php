<?php

namespace app\models;

use Yii;
use yii\web\Response;
use yii\web\IdentityInterface;
use app\models\MasterJabatan;
use app\models\MasterPenilaian;

/**
 * This is the model class for table "users".
 *
 * @property int $id_users
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string|null $access_token
 * @property int|null $id_jabatan
 * @property int|null $id_departement
 * @property int|null $level_jabatan
 * @property string $nama
 * @property string|null $tanggal_masuk
 * @property string|null $pendidikan_terakhir
 * @property string|null $status_karyawan
 * @property string|null $lokasi_kerja
 * @property string|null $atasan_langsung
 * @property string|null $nomor_hp
 * @property string|null $email
 * @property string|null $tanggal_lahir
 * @property string|null $jenis_kelamin
 * @property int|null $golongan
 * @property string|null $penilaian_terakhir
 * @property string|null $catatan_khusus
 * @property string|null $foto
 *
 * @property MasterDepartement $departement
 * @property MasterJabatan $jabatan
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    /**
     * ENUM field values
     */
    const JENIS_KELAMIN_PRIA = 'pria';
    const JENIS_KELAMIN_WANITA = 'wanita';

    public $fotoFile;

    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'id_jabatan', 'id_departement', 'level_jabatan', 'nama', 'tanggal_masuk', 'pendidikan_terakhir', 'status_karyawan', 'lokasi_kerja', 'atasan_langsung', 'nomor_hp', 'email', 'tanggal_lahir', 'jenis_kelamin', 'golongan'], 'required', 'message' => 'Tidak boleh kosong'],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9._]+$/', 
                'message' => 'Username hanya boleh berisi huruf, angka, titik, atau underscore, tanpa spasi.'],
            [['id_jabatan', 'id_departement', 'level_jabatan', 'golongan'], 'integer'],
            [['tanggal_masuk', 'tanggal_lahir', 'penilaian_terakhir'], 'safe'],
            [['password'], 'required', 'on' => 'create', 'message' => 'Tidak boleh kosong'],
            ['password', 'string', 'min' => 6],
            [['password'], 'safe', 'on' => 'update'],
            ['email', 'email', 'message' => 'Format email tidak valid'],
            ['nomor_hp', 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Hanya boleh angka'],
            [['jenis_kelamin'], 'string'],
            [['username', 'status_karyawan'], 'string', 'max' => 50],
            [['nama', 'pendidikan_terakhir', 'lokasi_kerja', 'atasan_langsung', 'email'], 'string', 'max' => 100],
            [['nomor_hp'], 'string', 'max' => 20],
            ['jenis_kelamin', 'in', 'range' => array_keys(self::optsJenisKelamin())],
            [['username'], 'unique'],
            [['foto'], 'string', 'max' => 255], 
            [['fotoFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['jpg','jpeg','png','webp'],
                'checkExtensionByMimeType' => true,
                'maxSize' => 2 * 1024 * 1024, // 2MB
            ],
            [['id_jabatan'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJabatan::class, 'targetAttribute' => ['id_jabatan' => 'id_jabatan']],
            [['id_departement'], 'exist', 'skipOnError' => true, 'targetClass' => MasterDepartement::class, 'targetAttribute' => ['id_departement' => 'id_departement']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['create'] = $scenarios[self::SCENARIO_DEFAULT];
        $scenarios['update'] = $scenarios[self::SCENARIO_DEFAULT];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_users' => 'Id Users',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'id_jabatan' => 'Id Jabatan',
            'id_departement' => 'Id Departement',
            'level_jabatan' => 'Level Jabatan',
            'nama' => 'Nama',
            'tanggal_masuk' => 'Tanggal Masuk',
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'status_karyawan' => 'Status Karyawan',
            'lokasi_kerja' => 'Lokasi Kerja',
            'atasan_langsung' => 'Atasan Langsung',
            'nomor_hp' => 'Nomor Hp',
            'email' => 'Email',
            'tanggal_lahir' => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'golongan' => 'Golongan',
            'penilaian_terakhir' => 'Penilaian Terakhir',
            'catatan_khusus' => 'Catatan Khusus',
            'foto' => 'Nama File Foto',
            'fotoFile' => 'Upload Foto',
        ];
    }

    /**
     * Gets query for [[BandingPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getBandingPenilaians()
    // {
    //     return $this->hasMany(BandingPenilaian::class, ['id_users' => 'id_users']);
    // }

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
     * Gets query for [[Jabatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatan()
    {
        return $this->hasOne(MasterJabatan::class, ['id_jabatan' => 'id_jabatan']);
    }

    public function getPeriode()
    {
        return $this->hasOne(MasterPeriode::class, ['id_periode' => 'id_periode']);
    }

    /**
     * Gets query for [[MasterEvents]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getMasterEvents()
    // {
    //     return $this->hasMany(MasterEvent::class, ['id_users' => 'id_users']);
    // }

    /**
     * Gets query for [[MasterPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getMasterPenilaians()
    // {
    //     return $this->hasMany(MasterPenilaian::class, ['id_users' => 'id_users']);
    // }

    /**
     * column jenis_kelamin ENUM value labels
     * @return string[]
     */
    public static function optsJenisKelamin()
    {
        return [
            self::JENIS_KELAMIN_PRIA => 'pria',
            self::JENIS_KELAMIN_WANITA => 'wanita',
        ];
    }

    /**
     * @return string
     */
    public function displayJenisKelamin()
    {
        return self::optsJenisKelamin()[$this->jenis_kelamin];
    }

    /**
     * @return bool
     */
    public function isJenisKelaminPria()
    {
        return $this->jenis_kelamin === self::JENIS_KELAMIN_PRIA;
    }

    public function setJenisKelaminToPria()
    {
        $this->jenis_kelamin = self::JENIS_KELAMIN_PRIA;
    }

    /**
     * @return bool
     */
    public function isJenisKelaminWanita()
    {
        return $this->jenis_kelamin === self::JENIS_KELAMIN_WANITA;
    }

    public function setJenisKelaminToWanita()
    {
        $this->jenis_kelamin = self::JENIS_KELAMIN_WANITA;
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; 
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id_users;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) return false;

        $pwd = trim((string)$this->password);

        if ($insert) { 
            if ($pwd === '') {
                $this->addError('password', 'Password wajib diisi.');
                return false;
            }
            $this->password_hash = Yii::$app->security->generatePasswordHash($pwd);
        } else {      
            if ($pwd !== '') {
                $this->password_hash = Yii::$app->security->generatePasswordHash($pwd);
            } else {
                $this->password_hash = $this->getOldAttribute('password_hash');
            }
        }

        foreach (['tanggal_masuk','tanggal_lahir','penilaian_terakhir'] as $attr) {
            if (!empty($this->$attr) &&
                preg_match('/^\d{2}-\d{2}-\d{4}( \d{2}:\d{2}:\d{2})?$/', $this->$attr)) {
                $this->$attr = strpos($this->$attr, ':') !== false
                    ? Yii::$app->formatter->asDatetime($this->$attr, 'php:Y-m-d H:i:s')
                    : Yii::$app->formatter->asDate($this->$attr, 'php:Y-m-d');
            }
        }

        if ($this->catatan_khusus === null || $this->catatan_khusus === '') {
            $this->catatan_khusus = 'Tidak ada';
        }

        return true;
    }
    
    public function afterFind()
    {
        parent::afterFind();

        foreach (['tanggal_masuk', 'tanggal_lahir', 'penilaian_terakhir'] as $attr) {
            if (!empty($this->$attr) && $this->$attr != '0000-00-00' && $this->$attr != '0000-00-00 00:00:00') {
                if (strpos($this->$attr, ':') !== false) {
                    $this->$attr = Yii::$app->formatter->asDatetime($this->$attr, 'php:d-m-Y H:i');
                } else {
                    $this->$attr = Yii::$app->formatter->asDate($this->$attr, 'php:d-m-Y');
                }
            }
        }
    }

    public function getFotoUrl(): string
    {
        if ($this->foto && file_exists(Yii::getAlias('@webroot/uploads/users/' . $this->foto))) {
            return Yii::getAlias('@web/uploads/users/' . $this->foto);
        }
        return Yii::getAlias('@web/images/no-avatar.jpeg'); 
    }

    public function actionGetLevelJabatan($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $jab = MasterJabatan::findOne($id);
        return ['level_jabatan' => $jab ? $jab->level_jabatan : null];
    }

   public function actionLatestPenilaian($id_users)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $latest = MasterPenilaian::find()
            ->joinWith('periode')
            ->where(['master_penilaian.id_users' => $id_users])
            ->orderBy(['master_periode.tanggal_selesai' => SORT_DESC])
            ->one();

        if ($latest && $latest->periode && $latest->periode->tanggal_selesai) {
            $tgl = Yii::$app->formatter->asDate(
                $latest->periode->tanggal_selesai,
                'php:d-m-Y'
            );

            return ['penilaian_terakhir' => $tgl];
        }

        return ['penilaian_terakhir' => null];
    }

    public function prefillFormValues(): void
    {
        if ($this->id_users) {
            $latest = MasterPenilaian::find()
                ->joinWith('periode')
                ->where(['master_penilaian.id_users' => $this->id_users])
                ->orderBy(['master_periode.tanggal_selesai' => SORT_DESC])
                ->one();

            if ($latest && $latest->periode && $latest->periode->tanggal_selesai) {
                $this->penilaian_terakhir = Yii::$app->formatter
                    ->asDate($latest->periode->tanggal_selesai, 'php:d-m-Y');
            }
        }

        if ($this->id_jabatan) {
            $jab = MasterJabatan::findOne($this->id_jabatan);
            if ($jab) {
                $this->level_jabatan = $jab->level_jabatan;
            }
        }
    }
}