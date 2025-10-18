<?php
namespace app\models\search;

use app\models\Content;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ContentSearch extends Model
{
    public $q;        // unified search
    public $status;   // optional filter
    public $perPage;  // page size (from toolbar)

    public function rules()
    {
        return [
            [['q','status'], 'safe'],
            [['perPage'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = Content::find()->orderBy(['updated_at' => SORT_DESC]);

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $this->perPage ?: (int)($params['per-page'] ?? 10)],
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]],
        ]);

        if (!$this->validate()) return $dataProvider;

        // unified text search across common fields
        if ($this->q) {
            $q = trim($this->q);
            $query->andFilterWhere([
                'or',
                ['like','title',$q],
                ['like','slug',$q],
                ['like','body',$q],
            ]);
        }

        if ($this->status) {
            $query->andWhere(['status' => $this->status]);
        }

        return $dataProvider;
    }
}
