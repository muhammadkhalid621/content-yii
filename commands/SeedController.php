<?php
namespace app\commands;

use yii\console\Controller;
use Yii;
use app\models\Tenant;
use app\models\User;
use app\models\Content;
use yii\helpers\Inflector;

class SeedController extends Controller
{
    public function actionDemo()
    {
        // Tenant
        $t = new Tenant();
        $t->name = 'Acme Media';
        $t->domain = 'acme.local';
        $t->created_at = $t->updated_at = time();
        $t->save(false);

        // Users
        foreach ([['admin','admin@acme.com','admin'],['editor','editor@acme.com','editor'],['viewer','viewer@acme.com','viewer']] as $row) {
            [$username, $email, $role] = $row;
            $u = new User();
            $u->tenant_id = $t->id;
            $u->username = $username;
            $u->email = $email;
            $u->auth_key = Yii::$app->security->generateRandomString(32);
            $u->setPassword('secret123');
            $u->created_at = $u->updated_at = time();
            $u->save(false);

            if ($r = Yii::$app->authManager->getRole($role)) {
                Yii::$app->authManager->assign($r, $u->id);
            }
        }

        // Content
        for ($i = 1; $i <= 6; $i++) {
            $c = new Content();
            $c->tenant_id = $t->id;
            $c->title = "Sample Post $i";
            $c->slug  = Inflector::slug($c->title) . '-' . substr(md5(microtime()), 0, 6);
            $c->body = "<p>Hello world $i</p>";
            $c->status = $i <= 4 ? 'published' : 'draft';
            $c->created_at = $c->updated_at = time();
            $c->save(false);
        }

        echo "Seeded demo data.\n";
    }
}
