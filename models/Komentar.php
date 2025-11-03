<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "komentar".
 *
 * @property int $id_komentar
 * @property int $id_users
 * @property int $id_event
 * @property string $deskripsi
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property MasterEvent $event
 * @property User $users
 */
class Komentar extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'komentar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_at'], 'default', 'value' => null],
            [['id_users', 'id_event', 'deskripsi'], 'required'],
            [['id_users', 'id_event'], 'integer'],
            [['deskripsi'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id_event'], 'exist', 'skipOnError' => true, 'targetClass' => MasterEvent::class, 'targetAttribute' => ['id_event' => 'id_event']],
            [['id_users'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_users' => 'id_users']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_komentar' => 'Id Komentar',
            'id_users' => 'Id Users',
            'id_event' => 'Id Event',
            'deskripsi' => 'Deskripsi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Event]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(MasterEvent::class, ['id_event' => 'id_event']);
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

}
