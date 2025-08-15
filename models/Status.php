<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property int $id_status
 * @property string $nama
 * @property string|null $deskripsi
 *
 * @property BandingPenilaian[] $bandingPenilaians
 */
class Status extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deskripsi'], 'default', 'value' => null],
            [['nama'], 'required'],
            [['deskripsi'], 'string'],
            [['nama'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_status' => 'Id Status',
            'nama' => 'Nama',
            'deskripsi' => 'Deskripsi',
        ];
    }

    /**
     * Gets query for [[BandingPenilaians]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getBandingPenilaians()
    // {
    //     return $this->hasMany(BandingPenilaian::class, ['id_status' => 'id_status']);
    // }

}
