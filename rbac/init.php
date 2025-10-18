<?php
namespace app\rbac;

use Yii;
use yii\console\Controller;

class InitController extends Controller
{
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // permissions
        $p = [];
        foreach ([
            'user.manage',
            'content.create','content.update','content.delete','content.publish','content.view',
            'file.upload','file.view','file.delete',
            'audit.view'
        ] as $perm) {
            $p[$perm] = $auth->createPermission($perm);
            $p[$perm]->description = $perm;
            $auth->add($p[$perm]);
        }

        // roles
        $admin  = $auth->createRole('admin');  $auth->add($admin);
        $editor = $auth->createRole('editor'); $auth->add($editor);
        $viewer = $auth->createRole('viewer'); $auth->add($viewer);

        // role permissions
        foreach ($p as $perm) { $auth->addChild($admin, $perm); }

        foreach (['content.create','content.update','content.publish','content.view','file.upload','file.view','audit.view'] as $name) {
            $auth->addChild($editor, $p[$name]);
        }
        foreach (['content.view','file.view'] as $name) {
            $auth->addChild($viewer, $p[$name]);
        }

        echo "RBAC initialized.\n";
    }
}
