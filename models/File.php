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
            [['tenant_id', 'original_name', 'path', 'mime_type', 'size'], 'required'],
            [['tenant_id', 'size', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['original_name', 'path', 'mime_type'], 'string', 'max' => 255],
            // soft validation hint for types
            ['mime_type', 'match', 'pattern' => '~^(image/|application/pdf|text/)~', 'message' => 'Only images, PDFs, or text files allowed.'],
            ['size', 'integer', 'min' => 1, 'max' => 20 * 1024 * 1024, 'tooBig' => 'Max 20MB'],
        ];
    }
}
