<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TglKerja;

/**
 * TglKerjaSearch represents the model behind the search form about `app\models\TglKerja`.
 */
class TglKerjaSearch extends TglKerja
{
    public $JenisJamker;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_jenis'], 'integer'],
            [['JenisJamker','label', 'tgl_awal', 'tgl_akhir'], 'safe'],
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
        $query = TglKerja::find()->joinWith(['jenisJamkerja']);

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
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
                ->andFilterWhere(['like','jenis_jamkerja.nama_jenis', $this->JenisJamker]);

        return $dataProvider;
    }
}
