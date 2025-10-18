<?php
namespace app\models\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use app\models\AuditLog;
use Yii;

class AuditBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    protected function write($action, $before, $after)
    {
        // Handle console vs web context
        $isConsole = Yii::$app instanceof \yii\console\Application;

        $ip = null;
        $ua = null;
        if (!$isConsole && Yii::$app->has('request')) {
            // Web request
            $ip = Yii::$app->request->userIP;
            $ua = Yii::$app->request->userAgent;
        } else {
            // Console run (seeding, cron, etc.)
            $ip = '127.0.0.1';
            $ua = 'console';
        }

        // Tenant might not be set in console; default to null or a known ID
        $tenantId = null;
        if (Yii::$app->has('tenant')) {
            $tenantId = Yii::$app->tenant->id ?? null;
        }

        $log = new AuditLog();
        $log->tenant_id = $tenantId;
        $log->user_id = Yii::$app->has('user') && !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        $log->action = $action;
        $log->model = get_class($this->owner);
        $log->model_id = (string)$this->owner->primaryKey;
        $log->before_json = $before ? json_encode($before) : null;
        $log->after_json  = $after ? json_encode($after) : null;
        $log->ip = $ip;
        $log->ua = $ua;
        $log->created_at = time();
        $log->updated_at = time();
        // Don't let audit failures block main save in console
        try { $log->save(false); } catch (\Throwable $e) { /* noop */ }
    }

    public function afterInsert()  { $this->write('create', null, $this->owner->attributes); }
    public function afterUpdate($e){ $this->write('update', $e->changedAttributes, $this->owner->attributes); }
    public function afterDelete()  { $this->write('delete', $this->owner->oldAttributes, null); }
}
