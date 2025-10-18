<?php
namespace app\models;

class AuditLog extends BaseTenantActiveRecord
{
    public static function tableName()
    {
        return 'audit_log';
    }
}
