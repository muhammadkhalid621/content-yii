<?php
namespace app\components;

use yii\base\BaseObject;
use Yii;

class TenantContext extends BaseObject
{
    public $id;

    public function init()
{
    parent::init();

    $header = Yii::$app->request->headers->get('X-Tenant-Id');
    if ($header !== null && $header !== '') {
        $this->id = (int)$header;
    } else {
        $this->id = (int)Yii::$app->session->get('tenant_id', 1); // default 1
    }

    // Persist the chosen tenant so Identity restore works consistently
    Yii::$app->session->set('tenant_id', $this->id);
}
}
