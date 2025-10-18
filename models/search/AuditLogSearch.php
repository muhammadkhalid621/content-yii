<?php
namespace app\models\search;

use app\models\AuditLog;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AuditLogSearch extends Model
{
    public $q;        // unified search
    public $perPage;

    public function rules()
    {
        return [
            [['q'], 'safe'],
            [['perPage'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = AuditLog::find()->orderBy(['created_at' => SORT_DESC]);

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $this->perPage ?: (int)($params['per-page'] ?? 10)],
        ]);

        if (!$this->validate()) return $dataProvider;

        if ($this->q) {
            $q = trim($this->q);
            $query->andFilterWhere([
                'or',
                ['like','action',$q],
                ['like','model',$q],
                ['like','model_id',$q],
                ['like','before_json',$q],
                ['like','after_json',$q],
                ['like','ip',$q],
                ['like','ua',$q],
            ]);
        }

        return $dataProvider;
    }
}
