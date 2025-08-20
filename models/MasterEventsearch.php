<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MasterEvent;

/**
 * MasterEventsearch represents the model behind the search form of `app\models\MasterEvent`.
 */
class MasterEventsearch extends MasterEvent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_event', 'id_users', 'id_departement', 'created_by'], 'integer'],
            [['judul', 'deskripsi', 'gambar', 'tanggal', 'jenis_event', 'severity', 'lokasi', 'status', 'updated_at'], 'safe'],
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
        $query = MasterEvent::find();

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
            'id_event' => $this->id_event,
            'id_users' => $this->id_users,
            'tanggal' => $this->tanggal,
            'id_departement' => $this->id_departement,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['like', 'gambar', $this->gambar])
            ->andFilterWhere(['like', 'jenis_event', $this->jenis_event])
            ->andFilterWhere(['like', 'severity', $this->severity])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
