<?php

// models/Notification.php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Notification extends ActiveRecord
{
    public static function tableName(){ return '{{%notification}}'; }

    public function rules()
    {
        return [
            [['target_id_user','judul','deskripsi','aksi'], 'required'],
            [['target_id_user'], 'integer'],
            [['deskripsi'], 'string'],
            [['dibaca'], 'boolean'],
            [['created_at'], 'safe'],
            [['judul'], 'string', 'max' => 150],
            [['aksi'], 'in', 'range' => ['create','update','delete']],
            [['model_class'], 'string', 'max' => 255],
            [['model_pk'], 'string', 'max' => 64],
        ];
    }
}
