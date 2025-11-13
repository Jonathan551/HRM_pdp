<?php
// app/models/MasterPeriodesearch.php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class MasterPeriodesearch extends MasterPeriode
{
    public function rules(): array
    {
        return [
            [['id_periode', 'id_user', 'created_at', 'updated_at'], 'integer'],
            [['nama', 'status', 'tanggal_mulai', 'tanggal_selesai'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MasterPeriode::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tanggal_mulai' => SORT_DESC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_periode' => $this->id_periode,
            'id_user'    => $this->id_user,
            'status'     => $this->status,
        ]);
        

        if (!empty($this->tanggal_mulai)) {
            $query->andFilterWhere(['>=', 'tanggal_mulai', $this->tanggal_mulai]);
        }
        if (!empty($this->tanggal_selesai)) {
            $query->andFilterWhere(['<=', 'tanggal_selesai', $this->tanggal_selesai]);
        }

        return $dataProvider;
    }
}
