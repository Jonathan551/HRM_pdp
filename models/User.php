<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

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
            [[ 'id_jabatan', 'id_departement', 'level_jabatan', 'tanggal_masuk', 'pendidikan_terakhir', 'status_karyawan', 'lokasi_kerja', 'atasan_langsung', 'nomor_hp', 'email', 'tanggal_lahir', 'jenis_kelamin', 'golongan', 'penilaian_terakhir', 'catatan_khusus'], 'default', 'value' => null],
            [['username', 'password', 'nama'], 'required'],
            [['id_jabatan', 'id_departement', 'level_jabatan', 'golongan'], 'integer'],
            [['tanggal_masuk', 'tanggal_lahir', 'penilaian_terakhir'], 'safe'],
            [['password'], $this->isNewRecord ? 'required' : 'safe'],
            [['jenis_kelamin'], 'string'],
            [['username', 'status_karyawan'], 'string', 'max' => 50],
            [['nama', 'pendidikan_terakhir', 'lokasi_kerja', 'atasan_langsung', 'email'], 'string', 'max' => 100],
            [['nomor_hp'], 'string', 'max' => 20],
            ['jenis_kelamin', 'in', 'range' => array_keys(self::optsJenisKelamin())],
            [['username'], 'unique'],
            [['id_jabatan'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJabatan::class, 'targetAttribute' => ['id_jabatan' => 'id_jabatan']],
            [['id_departement'], 'exist', 'skipOnError' => true, 'targetClass' => MasterDepartement::class, 'targetAttribute' => ['id_departement' => 'id_departement']],
        ];
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
        if (parent::beforeSave($insert)) {

            if (!empty($this->password)) {
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            }
            foreach (['tanggal_masuk', 'tanggal_lahir', 'penilaian_terakhir'] as $attr) {
                if (!empty($this->$attr) && preg_match('/\d{2}-\d{2}-\d{4}/', $this->$attr)) {
                    // Jika field datetime, tambahkan jam
                    if (strpos($this->$attr, ':') !== false) {
                        $this->$attr = Yii::$app->formatter->asDatetime($this->$attr, 'php:Y-m-d H:i:s');
                    } else {
                        $this->$attr = Yii::$app->formatter->asDate($this->$attr, 'php:Y-m-d');
                    }
                }
            }

            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();

        foreach (['tanggal_masuk', 'tanggal_lahir', 'penilaian_terakhir'] as $attr) {
            if (!empty($this->$attr) && $this->$attr != '0000-00-00' && $this->$attr != '0000-00-00 00:00:00') {
                // Jika ada waktu
                if (strpos($this->$attr, ':') !== false) {
                    $this->$attr = Yii::$app->formatter->asDatetime($this->$attr, 'php:d-m-Y H:i');
                } else {
                    $this->$attr = Yii::$app->formatter->asDate($this->$attr, 'php:d-m-Y');
                }
            }
        }
    }
}