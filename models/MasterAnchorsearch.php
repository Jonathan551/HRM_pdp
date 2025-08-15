<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MasterAnchor;

/**
 * MasterAnchorsearch represents the model behind the search form of `app\models\MasterAnchor`.
 */
class MasterAnchorsearch extends MasterAnchor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_anchor', 'id_kriteria', 'level_anchor', 'nilai_anchor'], 'integer'],
            [['deskripsi'], 'safe'],
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
        $query = MasterAnchor::find();

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
            'id_anchor' => $this->id_anchor,
            'id_kriteria' => $this->id_kriteria,
            'level_anchor' => $this->level_anchor,
            'nilai_anchor' => $this->nilai_anchor,
        ]);

        $query->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}
