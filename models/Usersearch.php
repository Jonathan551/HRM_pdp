<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * Usersearch represents the model behind the search form of `app\models\User`.
 */
class Usersearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_users', 'id_jabatan', 'id_departement', 'level_jabatan', 'golongan'], 'integer'],
            [['username', 'password_hash', 'nama', 'tanggal_masuk', 'pendidikan_terakhir', 'status_karyawan', 'lokasi_kerja', 'atasan_langsung', 'nomor_hp', 'email', 'tanggal_lahir', 'jenis_kelamin', 'penilaian_terakhir', 'catatan_khusus'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_users' => $this->id_users,
            'id_jabatan' => $this->id_jabatan,
            'id_departement' => $this->id_departement,
            'level_jabatan' => $this->level_jabatan,
            'tanggal_masuk' => $this->tanggal_masuk,
            'tanggal_lahir' => $this->tanggal_lahir,
            'golongan' => $this->golongan,
            'penilaian_terakhir' => $this->penilaian_terakhir,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'pendidikan_terakhir', $this->pendidikan_terakhir])
            ->andFilterWhere(['like', 'status_karyawan', $this->status_karyawan])
            ->andFilterWhere(['like', 'lokasi_kerja', $this->lokasi_kerja])
            ->andFilterWhere(['like', 'atasan_langsung', $this->atasan_langsung])
            ->andFilterWhere(['like', 'nomor_hp', $this->nomor_hp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'catatan_khusus', $this->catatan_khusus]);

        return $dataProvider;
    }
}
    