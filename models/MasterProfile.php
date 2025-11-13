<?php

namespace app\models;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "master_profile".
 *
 * @property int $id_profile
 * @property string $nama
 * @property int $notelfon
 * @property string $email
 * @property string $alamat
 * @property string $logo
 */
class MasterProfile extends ActiveRecord
{

    public $file_logo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'notelfon', 'email', 'alamat', 'logo'], 'required'],
            [['logo'], 'string'],
            [['nama', 'email', 'alamat','notelfon'], 'string', 'max' => 255],
            [['file_logo'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_profile' => 'Id Profile',
            'nama' => 'Nama',
            'notelfon' => 'Notelfon',
            'email' => 'Email',
            'alamat' => 'Alamat',
            'logo' => 'Logo',
            'file_logo' => 'Upload Logo',
        ];
    }

}
