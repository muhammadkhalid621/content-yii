<?php
namespace app\models\search;

use app\models\File;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FileSearch extends Model
{
    public $q;       // unified search
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
        $query = File::find()->orderBy(['created_at' => SORT_DESC]);

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
                ['like','original_name',$q],
                ['like','mime_type',$q],
                ['like','path',$q],
            ]);
        }

        return $dataProvider;
    }
}
