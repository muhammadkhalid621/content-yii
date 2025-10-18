<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class BaseTenantActiveRecord extends ActiveRecord
{
    public static function find()
    {
        $query = parent::find();
        $instance = new static();
        if ($instance->hasAttribute('tenant_id')) {
            $query->andWhere([static::tableName().'.tenant_id' => Yii::$app->tenant->id]);
        }
        return $query;
    }

    public function beforeValidate()
    {
        if ($this->hasAttribute('tenant_id') && $this->tenant_id === null) {
            $this->tenant_id = Yii::$app->tenant->id;
        }
        return parent::beforeValidate();
    }
}
