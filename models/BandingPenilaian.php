<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class BandingPenilaian extends ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'tanggal_banding', 
                'updatedAtAttribute' => false,            
                'value' => new Expression('NOW()'),        
            ],
        ];
    }

    const STATUS_REVIEW   = 'Review';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK  = 'Ditolak';


    public static function tableName()
    {
        return 'banding_penilaian';
    }

    public static function optsStatus(): array
    {
        return [
            self::STATUS_REVIEW   => 'Review',
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_DITOLAK  => 'Ditolak',
        ];
    }

    public function rules()
    {
        return [
            [['id_penilaian', 'id_users'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_REVIEW],
            [['status'], 'in', 'range' => array_keys(self::optsStatus())],
            [['alasan', 'review'], 'string'],
            ['alasan', 'required', 'message' => 'Alasan banding wajib diisi.'],
            ['alasan', 'trim'],
            ['alasan', 'string', 'max' => 5000],
            [['tanggal_banding', 'tanggal_review'], 'safe'],
            [['id_penilaian'], 'exist', 'skipOnError' => true,
                'targetClass' => MasterPenilaian::class, 'targetAttribute' => ['id_penilaian' => 'id_penilaian']],
            [['id_users'], 'exist', 'skipOnError' => true,
                'targetClass' => User::class, 'targetAttribute' => ['id_users' => 'id_users']],
            ['status', function ($attribute) {
                if ($this->isNewRecord) return;
                $old = $this->getOldAttribute('status');
                $new = $this->$attribute;
                if ($new === $old) return;

                $allowed = ($old === self::STATUS_REVIEW) && in_array($new, [self::STATUS_DITERIMA, self::STATUS_DITOLAK], true);
                if (!$allowed) {
                    $this->addError($attribute, 'Keputusan sudah final dan tidak bisa diubah.');
                }
            }],
            [['alasan', 'review'], function ($attribute) {
                if (!$this->isNewRecord && $this->getOldAttribute('status') !== self::STATUS_REVIEW) {
                    if ($this->$attribute !== $this->getOldAttribute($attribute)) {
                        $this->addError($attribute, 'Data banding sudah final dan tidak dapat diubah.');
                    }
                }
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_banding'       => 'Id Banding',
            'id_penilaian'     => 'Id Penilaian',
            'id_users'         => 'Id Users',
            'status'           => 'Status',
            'tanggal_banding'  => 'Tanggal Banding',
            'alasan'           => 'Alasan',
            'review'           => 'Review',
            'tanggal_review'   => 'Tanggal Review',
        ];
    }

   public function getPenilaian(){ return $this->hasOne(MasterPenilaian::class, ['id_penilaian' => 'id_penilaian']); }
   public function getUser(){return $this->hasOne(User::class, ['id_users' => 'id_users']);}
   public function displayStatus(): string{ return self::optsStatus()[$this->status] ?? $this->status; }
   public function getDetailPenilaian(){ return $this->hasMany(DetailPenilaian::class, ['id_penilaian'=>'id_penilaian']); }
   public function getKategori(){ return $this->hasOne(MasterKategori::class, ['id_kategori'=>'id_kategori']); }
   public function isStatusReview(): bool   { return $this->status === self::STATUS_REVIEW; }
   public function isStatusDiterima(): bool { return $this->status === self::STATUS_DITERIMA; }
   public function isStatusDitolak(): bool  { return $this->status === self::STATUS_DITOLAK; }
   public function setStatusToReview(): void   { $this->status = self::STATUS_REVIEW; }
   public function setStatusToDiterima(): void { $this->status = self::STATUS_DITERIMA; }
   public function setStatusToDitolak(): void  { $this->status = self::STATUS_DITOLAK; }
   public function getPeriode(){return $this->hasOne(MasterPeriode::class, ['id_periode' => 'id_periode']);}

   public function getTanggalBandingDisplay(): string
    {
        $v = $this->tanggal_banding;
        if (empty($v) || $v === '0000-00-00' || $v === '0000-00-00 00:00:00') {
            return '-';
        }
        return Yii::$app->formatter->asDatetime($v, 'php:d-m-Y ');
    }

    public function getTanggalReviewDisplay(): string
    {
        $v = $this->tanggal_review;
        if (empty($v) || $v === '0000-00-00' || $v === '0000-00-00 00:00:00') {
            return '-';
        }
        return Yii::$app->formatter->asDatetime($v, 'php:d-m-Y ');
    }

     public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) return false;

        if (!$insert) {
            $old = $this->getOldAttribute('status');
            if (
                $old === self::STATUS_REVIEW &&
                in_array($this->status, [self::STATUS_DITERIMA, self::STATUS_DITOLAK], true) &&
                empty($this->tanggal_review)
            ) {
                $this->tanggal_review = new Expression('NOW()');
            }
        }

        return true;
    }
}
