<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KeteranganAbsen;

/**
 * KeteranganAbsenSearch represents the model behind the search form about `app\models\KeteranganAbsen`.
 */
class KeteranganAbsenSearch extends KeteranganAbsen
{
    /**
     * @inheritdoc
     */
    
    public $username;
    public $pin;


    public function rules()
    {
        return [
            [['id', 'userid','pin'], 'integer'],
            [['username','statusid', 'tgl_awal', 'tgl_akhir', 'keterangan'], 'safe'],
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
        $query = KeteranganAbsen::find()->joinWith('userinfo');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=>['tgl_awal'=>SORT_DESC]]
        ]);
        $dataProvider->sort->attributes['username'] = [
            'asc' => ['userinfo.name'=>SORT_ASC],
            'desc' => ['userinfo.name'=>SORT_DESC]
        ];
        $dataProvider->sort->attributes['pin'] = [
            'asc' => ['userinfo.badgenumber'=>SORT_ASC],
            'desc' => ['userinfo.badgenumber'=>SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'userid' => $this->userid,
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir,
        ]);

        $query->andFilterWhere(['like', 'statusid', $this->statusid])
            ->andFilterWhere(['like','userinfo.badgenumber', $this->pin])
            ->andFilterWhere(['like','userinfo.name', $this->username])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
