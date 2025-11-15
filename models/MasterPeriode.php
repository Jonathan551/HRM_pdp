<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property int $id_periode
 * @property string $nama
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property int $id_user
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property MasterPenilaian[] $masterPenilaians
 * @property User $user
 */
class MasterPeriode extends ActiveRecord
{
    public static function tableName(): string { return '{{%master_periode}}'; }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'), 
            ],
        ];
    }
    public function rules(): array
    {
        return [
            [['nama','tanggal_mulai','tanggal_selesai','id_user'], 'required'],
            [['tanggal_mulai','tanggal_selesai'], 'date', 'format' => 'php:Y-m-d'],
            [['id_user'], 'integer'],
            [['nama'], 'string', 'max' => 100],
            [['status'], 'in', 'range' => ['Terbuka','Tertutup']],
            ['status', 'default', 'value' => 'Terbuka'], 
            ['tanggal_selesai', 'compare', 'compareAttribute' => 'tanggal_mulai', 'operator' => '>=', 'type' => 'date' , 'message'=>'Tanggal Selesai Tidak Boleh lebih besar dari Tanggal Mulai'],
        ];
    }
    
    public function attributeLabels(): array
    {
        return [
            'nama'            => 'Nama Periode',
            'tanggal_mulai'   => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'id_user'         => 'Owner',
            'status'          => 'Status',
        ];
    }

    public function getOwner()      { return $this->hasOne(User::class, ['id_users' => 'id_user']); }
    public function getPenilaians() { return $this->hasMany(MasterPenilaian::class, ['id_periode' => 'id_periode']); }
    public function beforeValidate(): bool
    {
        if ($this->isNewRecord && empty($this->id_user)) {
            $this->id_user = Yii::$app->user->id;
        }
        return parent::beforeValidate();
    }

    public function autoGeneratePenilaian(): int
    {
        $users = User::find()
            ->where(['<>', 'id_users', $this->id_user])
            ->all();

        $count = 0;
        $tx = Yii::$app->db->beginTransaction();
        try {
            foreach ($users as $u) {
                $exists = MasterPenilaian::find()
                    ->where(['id_periode'=>$this->id_periode,'id_users'=>$u->id_users])
                    ->exists();
                if ($exists) continue;

                $mp = new MasterPenilaian(['scenario'=>'autoCreate']);
                $mp->id_users   = $u->id_users;
                $mp->id_periode = $this->id_periode;
                $mp->save(false); 
                $count++;
            }
            $tx->commit();
            return $count;
        } catch (\Throwable $e) {
            $tx->rollBack();
            Yii::error('Auto-generate penilaian gagal: '.$e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) { $this->autoGeneratePenilaian(); }
    }
}

