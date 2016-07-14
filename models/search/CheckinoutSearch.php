<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Checkinout;

/**
 * CheckinoutSearch represents the model behind the search form about `app\models\Checkinout`.
 */
class CheckinoutSearch extends Checkinout
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'verifycode'], 'integer'],
            [['checktime', 'checktype', 'SN', 'sensorid', 'WorkCode', 'Reserved'], 'safe'],
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
        $query = Checkinout::find();

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
            'userid' => $this->userid,
            'checktime' => $this->checktime,
            'verifycode' => $this->verifycode,
        ]);

        $query->andFilterWhere(['like', 'checktype', $this->checktype])
            ->andFilterWhere(['like', 'SN', $this->SN])
            ->andFilterWhere(['like', 'sensorid', $this->sensorid])
            ->andFilterWhere(['like', 'WorkCode', $this->WorkCode])
            ->andFilterWhere(['like', 'Reserved', $this->Reserved]);

        return $dataProvider;
    }
}