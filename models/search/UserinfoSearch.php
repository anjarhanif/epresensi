<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Userinfo;

/**
 * UserinfoSearch represents the model behind the search form about `app\models\Userinfo`.
 */
class UserinfoSearch extends Userinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'defaultdeptid', 'Privilege', 'AccGroup', 'SECURITYFLAGS', 'DelTag', 'RegisterOT', 'AutoSchPlan', 'MinAutoSchInterval', 'Image_id'], 'integer'],
            [['badgenumber', 'name', 'Password', 'Card', 'TimeZones', 'Gender', 'Birthday', 'street', 'zip', 'ophone', 'FPHONE', 'pager', 'minzu', 'title', 'SN', 'SSN', 'UTime', 'State', 'City'], 'safe'],
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
        $query = Userinfo::find();

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
            'userid' => $this->userid,
            'defaultdeptid' => $this->defaultdeptid,
            'Privilege' => $this->Privilege,
            'AccGroup' => $this->AccGroup,
            'Birthday' => $this->Birthday,
            'UTime' => $this->UTime,
            'SECURITYFLAGS' => $this->SECURITYFLAGS,
            'DelTag' => $this->DelTag,
            'RegisterOT' => $this->RegisterOT,
            'AutoSchPlan' => $this->AutoSchPlan,
            'MinAutoSchInterval' => $this->MinAutoSchInterval,
            'Image_id' => $this->Image_id,
        ]);

        $query->andFilterWhere(['like', 'badgenumber', $this->badgenumber])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'Password', $this->Password])
            ->andFilterWhere(['like', 'Card', $this->Card])
            ->andFilterWhere(['like', 'TimeZones', $this->TimeZones])
            ->andFilterWhere(['like', 'Gender', $this->Gender])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'zip', $this->zip])
            ->andFilterWhere(['like', 'ophone', $this->ophone])
            ->andFilterWhere(['like', 'FPHONE', $this->FPHONE])
            ->andFilterWhere(['like', 'pager', $this->pager])
            ->andFilterWhere(['like', 'minzu', $this->minzu])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'SN', $this->SN])
            ->andFilterWhere(['like', 'SSN', $this->SSN])
            ->andFilterWhere(['like', 'State', $this->State])
            ->andFilterWhere(['like', 'City', $this->City]);

        return $dataProvider;
    }
}
