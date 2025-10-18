<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\Inflector;
use app\models\behaviors\AuditBehavior;

class Content extends BaseTenantActiveRecord
{
    public static function tableName()
    {
        return 'content';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            AuditBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['title','body','status'], 'required'],
            ['status', 'in', 'range' => ['draft','published','archived']],
            [['slug'], 'unique'],
        ];
    }

    public function beforeValidate()
    {
        if (!$this->slug && $this->title) {
            $this->slug = Inflector::slug($this->title) . '-' . substr(md5(microtime()),0,6);
        }
        return parent::beforeValidate();
    }
}
