<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MasterPenilaian;

/**
 * MasterPenilaiansearch represents the model behind the search form of `app\models\MasterPenilaian`.
 */
class MasterPenilaiansearch extends MasterPenilaian
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_penilaian', 'id_users'], 'integer'],
            [['nilai_akhir'], 'number'],
            [['periode_awal', 'periode_akhir', 'status', 'presentase_absensi'], 'safe'],
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
        $query = MasterPenilaian::find();

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
            'id_penilaian' => $this->id_penilaian,
            'id_users' => $this->id_users,
            'nilai_akhir' => $this->nilai_akhir,
            'periode_awal' => $this->periode_awal,
            'periode_akhir' => $this->periode_akhir,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'presentase_absensi', $this->presentase_absensi]);

        return $dataProvider;
    }
}
