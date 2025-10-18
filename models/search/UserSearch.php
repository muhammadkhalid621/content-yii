<?php
namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

class UserSearch extends User
{
    public $globalSearch;

    public function rules()
    {
        return [
            [['globalSearch'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = User::find()->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if ($this->globalSearch) {
            $query->andFilterWhere(['or',
                ['like', 'username', $this->globalSearch],
                ['like', 'email', $this->globalSearch],
                ['like', 'tenant_id', $this->globalSearch],
            ]);
        }

        return $dataProvider;
    }
}
