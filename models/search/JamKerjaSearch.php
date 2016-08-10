<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JamKerja;

/**
 * JamKerjaSearch represents the model behind the search form about `app\models\JamKerja`.
 */
class JamKerjaSearch extends JamKerja
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nama_jamker', 'jam_masuk', 'jam_pulang', 'mulai_cekin', 'akhir_cekout'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = JamKerja::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'jam_masuk' => $this->jam_masuk,
            'jam_pulang' => $this->jam_pulang,
            'mulai_cekin' => $this->mulai_cekin,
            'akhir_cekout' => $this->akhir_cekout,
        ]);

        $query->andFilterWhere(['like', 'nama_jamker', $this->nama_jamker]);

        return $dataProvider;
    }
}
