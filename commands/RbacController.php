<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // create RBAC tables first if you haven't:
        // php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0

        $auth->removeAll();

        // permissions
        $perms = [
            'user.manage',
            'content.create','content.update','content.delete','content.publish','content.view',
            'file.upload','file.view','file.delete',
            'audit.view'
        ];
        foreach ($perms as $name) {
            if (!$auth->getPermission($name)) {
                $p = $auth->createPermission($name);
                $p->description = $name;
                $auth->add($p);
            }
        }

        // roles
        $admin  = $auth->getRole('admin')  ?: $auth->createRole('admin');  $auth->add($admin);
        $editor = $auth->getRole('editor') ?: $auth->createRole('editor'); $auth->add($editor);
        $viewer = $auth->getRole('viewer') ?: $auth->createRole('viewer'); $auth->add($viewer);

        // role permissions
        foreach ($perms as $name) {
            $auth->addChild($admin, $auth->getPermission($name));
        }
        foreach (['content.create','content.update','content.publish','content.view','file.upload','file.view','audit.view'] as $n) {
            $auth->addChild($editor, $auth->getPermission($n));
        }
        foreach (['content.view','file.view'] as $n) {
            $auth->addChild($viewer, $auth->getPermission($n));
        }

        $this->stdout("RBAC initialized.\n");
    }
}
