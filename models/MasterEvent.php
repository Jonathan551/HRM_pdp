<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_event".
 *
 * @property int $id_event
 * @property int|null $id_users
 * @property string|null $judul
 * @property string|null $deskripsi
 * @property string|null $gambar
 * @property string|null $tanggal
 * @property string|null $jenis_event
 * @property string|null $severity
 * @property string|null $lokasi
 * @property int|null $id_departemen
 * @property int|null $created_by
 * @property string|null $status
 * @property string $updated_at
 *
 * @property MasterDepartement $departemen
 * @property Users $users
 */
class MasterEvent extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';
    const STATUS_OPEN = 'open';
    const STATUS_REVIEW = 'review';
    const STATUS_CLOSED = 'closed';

    public $uploadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_users', 'judul', 'deskripsi', 'tanggal', 'jenis_event', 'lokasi', 'created_by'], 'default', 'value' => null],
            [['severity'], 'default', 'value' => 'low'],
            [['status'], 'default', 'value' => 'open'],
            [['id_users', 'id_departement', 'created_by'], 'integer'],
            [['deskripsi', 'severity', 'status'], 'string'],
            [['tanggal', 'updated_at'], 'safe'],
            [['judul'], 'string', 'max' => 150],
            [['gambar'], 'string', 'max' => 255],
            [['jenis_event'], 'string', 'max' => 50],
            [['lokasi'], 'string', 'max' => 100],
            ['severity', 'in', 'range' => array_keys(self::optsSeverity())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['id_users'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_users' => 'id_users']],
            [['id_departement'], 'exist', 'skipOnError' => true, 'targetClass' => MasterDepartement::class, 'targetAttribute' => ['id_departement' => 'id_departement']],
            [['uploadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_event' => 'Id Event',
            'id_users' => 'Id Users',
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'gambar' => 'Gambar',
            'tanggal' => 'Tanggal',
            'jenis_event' => 'Jenis Event',
            'severity' => 'Severity',
            'lokasi' => 'Lokasi',
            'id_departement' => 'Id Departemen',
            'created_by' => 'Created By',
            'status' => 'Status',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(User::class, ['id_users' => 'id_users']);
    }

    public function getDepartement()
    {
        return $this->hasOne(MasterDepartement::class, ['id_departement' => 'id_departement']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id_users' => 'created_by']);
    }

    /**
     * column severity ENUM value labels
     * @return string[]
     */
    public static function optsSeverity()
    {
        return [
            self::SEVERITY_LOW => 'low',
            self::SEVERITY_MEDIUM => 'medium',
            self::SEVERITY_HIGH => 'high',
            self::SEVERITY_CRITICAL => 'critical',
        ];
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_OPEN => 'open',
            self::STATUS_REVIEW => 'review',
            self::STATUS_CLOSED => 'closed',
        ];
    }

    /**
     * @return string
     */
    public function displaySeverity()
    {
        return self::optsSeverity()[$this->severity];
    }

    /**
     * @return bool
     */
    public function isSeverityLow()
    {
        return $this->severity === self::SEVERITY_LOW;
    }

    public function setSeverityToLow()
    {
        $this->severity = self::SEVERITY_LOW;
    }

    /**
     * @return bool
     */
    public function isSeverityMedium()
    {
        return $this->severity === self::SEVERITY_MEDIUM;
    }

    public function setSeverityToMedium()
    {
        $this->severity = self::SEVERITY_MEDIUM;
    }

    /**
     * @return bool
     */
    public function isSeverityHigh()
    {
        return $this->severity === self::SEVERITY_HIGH;
    }

    public function setSeverityToHigh()
    {
        $this->severity = self::SEVERITY_HIGH;
    }

    /**
     * @return bool
     */
    public function isSeverityCritical()
    {
        return $this->severity === self::SEVERITY_CRITICAL;
    }

    public function setSeverityToCritical()
    {
        $this->severity = self::SEVERITY_CRITICAL;
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusOpen()
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function setStatusToOpen()
    {
        $this->status = self::STATUS_OPEN;
    }

    /**
     * @return bool
     */
    public function isStatusReview()
    {
        return $this->status === self::STATUS_REVIEW;
    }

    public function setStatusToReview()
    {
        $this->status = self::STATUS_REVIEW;
    }

    /**
     * @return bool
     */
    public function isStatusClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function setStatusToClosed()
    {
        $this->status = self::STATUS_CLOSED;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_by = Yii::$app->user->id;
            }
            $user = User::findOne($this->id_users);
            $this->id_departement = $user && $user->id_departement? $user->id_departement : null;

            if ($this->uploadFile) {
                $fileName = uniqid() . '.' . $this->uploadFile->extension;
                $path = Yii::getAlias('@webroot/uploads/') . $fileName;
                if ($this->uploadFile->saveAs($path)) {
                    $this->gambar = $fileName; 
                }
            }
            foreach (['tanggal'] as $attr) {
                    if (!empty($this->$attr) && preg_match('/\d{2}-\d{2}-\d{4}/', $this->$attr)) {
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

        foreach (['tanggal'] as $attr) {
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
