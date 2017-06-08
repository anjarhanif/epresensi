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
    public $jenisJamker;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_jenis'], 'integer'],
            [['jenisJamker','no_hari', 'jam_masuk', 'jam_pulang', 'mulai_cekin', 'akhir_cekout'], 'safe'],
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
        $query = JamKerja::find()->joinWith(['jenisJamkerja']);

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
            'id_jenis' => $this->id_jenis,
            'jam_masuk' => $this->jam_masuk,
            'jam_pulang' => $this->jam_pulang,
            'mulai_cekin' => $this->mulai_cekin,
            'akhir_cekout' => $this->akhir_cekout,
        ]);

        $query->andFilterWhere(['like', 'no_hari', $this->no_hari])
                ->andFilterWhere(['like','jenis_jamkerja.nama_jenis', $this->jenisJamker]);

        return $dataProvider;
    }
}
