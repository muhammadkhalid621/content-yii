<?php

use yii\db\Migration;

class m240000_000001_add_password_reset_token_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'password_reset_token', $this->string(255)->after('password_hash'));
        $this->createIndex('idx_user_password_reset_token', '{{%user}}', 'password_reset_token', true);
    }
    public function safeDown()
    {
        $this->dropIndex('idx_user_password_reset_token', '{{%user}}');
        $this->dropColumn('{{%user}}', 'password_reset_token');
    }
}
