<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_kategori".
 *
 * @property int $id_kategori
 * @property string $nama_kategori
 * @property float $nilai_min
 * @property float $nilai_max
 */
class MasterKategori extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_kategori', 'nilai_min', 'nilai_max'], 'required'],
            [['nilai_min', 'nilai_max'], 'number'],
            [['nama_kategori'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_kategori' => 'Id Kategori',
            'nama_kategori' => 'Nama Kategori',
            'nilai_min' => 'Nilai Min',
            'nilai_max' => 'Nilai Max',
        ];
    }

}
