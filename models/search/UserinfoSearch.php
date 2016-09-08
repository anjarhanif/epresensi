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
    public $deptname;
    
    public function rules()
    {
        return [
            [['userid', 'defaultdeptid', 'Privilege', 'AccGroup', 'SECURITYFLAGS', 'DelTag', 'RegisterOT', 'AutoSchPlan', 'MinAutoSchInterval', 'Image_id'], 'integer'],
            [['deptname','badgenumber', 'name', 'Password', 'Card', 'TimeZones', 'Gender', 'Birthday', 'street', 'zip', 'ophone', 'FPHONE', 'pager', 'minzu', 'title', 'SN', 'SSN', 'UTime', 'State', 'City'], 'safe'],
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
        $query = Userinfo::find()->joinWith('department');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['deptname'] = [
            'asc'=>['departments.DeptName'=>SORT_ASC],
            'desc'=>['departments.DeptName'=>SORT_DESC]
        ];

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
            'departments.deptname' => $this->deptname,
            'Birthday' => $this->Birthday,
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
