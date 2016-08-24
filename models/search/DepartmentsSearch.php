<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Departments;

/**
 * DepartmentsSearch represents the model behind the search form about `app\models\Departments`.
 */
class DepartmentsSearch extends Departments
{
    /**
     * @inheritdoc
     */
    public $sup_dept;
    
    public function rules()
    {
        return [
            [['DeptID', 'supdeptid'], 'integer'],
            [['DeptName','sup_dept'], 'safe'],
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
        $query = Departments::find()->joinWith('supdept');

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
            'DeptID' => $this->DeptID,
            'supdeptid' => $this->supdeptid,
        ]);

        $query->andFilterWhere(['like', 'DeptName', $this->DeptName])
                ->andFilterWhere(['like', 'sup_dept.DeptName', $this->sup_dept]);

        return $dataProvider;
    }
}
