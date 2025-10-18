<?php
namespace app\models;

class Tenant extends BaseTenantActiveRecord
{
    public static function tableName()
    {
        return 'tenant';
    }
}
