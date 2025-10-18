<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\models\behaviors\AuditBehavior;

class File extends BaseTenantActiveRecord
{
    public $upload;

    public static function tableName()
    {
        return 'file';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class, AuditBehavior::class];
    }

    public function rules()
    {
        return [
            [['original_name','path'], 'required'],
            [['mime_type'], 'string', 'max' => 100],
            [['size'], 'integer'],
        ];
    }
}
