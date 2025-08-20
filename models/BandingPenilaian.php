<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banding_penilaian".
 *
 * @property int $id_banding
 * @property int|null $id_penilaian
 * @property int|null $id_users
 * @property string|null $status
 * @property string|null $tanggal_banding
 * @property string|null $alasan
 * @property string|null $review
 * @property string|null $tanggal_review
 *
 * @property MasterPenilaian $penilaian
 * @property User $users
 */
class BandingPenilaian extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_REVIEW = 'Review';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK = 'Ditolak';
    const STATUS = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banding_penilaian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_penilaian', 'id_users', 'tanggal_banding', 'alasan', 'tanggal_review'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'Review'],
            [['id_penilaian', 'id_users'], 'integer'],
            [['status', 'alasan', 'review'], 'string'],
            [['tanggal_banding', 'tanggal_review' , 'review'], 'safe'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['id_penilaian'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPenilaian::class, 'targetAttribute' => ['id_penilaian' => 'id_penilaian']],
            [['id_users'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_users' => 'id_users']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_banding' => 'Id Banding',
            'id_penilaian' => 'Id Penilaian',
            'id_users' => 'Id Users',
            'status' => 'Status',
            'tanggal_banding' => 'Tanggal Banding',
            'alasan' => 'Alasan',
            'review' => 'Review',
            'tanggal_review' => 'Tanggal Review',
        ];
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

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id_users' => 'id_users']);
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_REVIEW => 'Review',
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS => '',
        ];
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
    public function isStatusDiterima()
    {
        return $this->status === self::STATUS_DITERIMA;
    }

    public function setStatusToDiterima()
    {
        $this->status = self::STATUS_DITERIMA;
    }

    /**
     * @return bool
     */
    public function isStatusDitolak()
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    public function setStatusToDitolak()
    {
        $this->status = self::STATUS_DITOLAK;
    }

    /**
     * @return bool
     */
    public function isStatus()
    {
        return $this->status === self::STATUS;
    }

    public function setStatusTo()
    {
        $this->status = self::STATUS;
    }
}
