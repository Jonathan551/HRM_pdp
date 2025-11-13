<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class MasterPenilaiansearch extends MasterPenilaian
{
    public function rules(): array
    {
        return [
            [['id_penilaian','id_users','id_periode','created_at','updated_at'], 'integer'],
            [['catatan','rekomendasi'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MasterPenilaian::find()->with(['user','periode']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_penilaian' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_penilaian' => $this->id_penilaian,
            'id_users'     => $this->id_users,
            'id_periode'   => $this->id_periode,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ]);

        $query->andFilterWhere(['like','catatan',$this->catatan])
              ->andFilterWhere(['like','rekomendasi',$this->rekomendasi]);

        return $dataProvider;
    }
}