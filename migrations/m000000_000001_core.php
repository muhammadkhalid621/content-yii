<?php
use yii\db\Migration;

class m000000_000001_core extends Migration
{
    public function safeUp()
    {
        $this->createTable('tenant',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string(150)->notNull(),
            'domain'=>$this->string(150),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
        ]);

        $this->createTable('user',[
            'id'=>$this->primaryKey(),
            'tenant_id'=>$this->integer()->notNull(),
            'username'=>$this->string(80)->notNull()->unique(),
            'email'=>$this->string(150)->notNull()->unique(),
            'password_hash'=>$this->string()->notNull(),
            'auth_key'=>$this->string(64)->notNull(),
            'status'=>$this->smallInteger()->notNull()->defaultValue(10),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_user_tenant','user','tenant_id','tenant','id');

        $this->createTable('content',[
            'id'=>$this->primaryKey(),
            'tenant_id'=>$this->integer()->notNull(),
            'title'=>$this->string()->notNull(),
            'slug'=>$this->string()->notNull()->unique(),
            'body'=>$this->text()->notNull(),
            'status'=>"ENUM('draft','published','archived') NOT NULL DEFAULT 'draft'",
            'published_at'=>$this->integer(),
            'created_by'=>$this->integer(),
            'updated_by'=>$this->integer(),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_content_tenant','content','tenant_id','tenant','id');

        $this->createTable('file',[
            'id'=>$this->primaryKey(),
            'tenant_id'=>$this->integer()->notNull(),
            'original_name'=>$this->string()->notNull(),
            'path'=>$this->string()->notNull(),
            'mime_type'=>$this->string(100),
            'size'=>$this->bigInteger(),
            'created_by'=>$this->integer(),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_file_tenant','file','tenant_id','tenant','id');

        $this->createTable('audit_log',[
            'id'=>$this->primaryKey(),
            'tenant_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'action'=>$this->string(64)->notNull(),
            'model'=>$this->string(128),
            'model_id'=>$this->string(64),
            'before_json'=>$this->text(),
            'after_json'=>$this->text(),
            'ip'=>$this->string(45),
            'ua'=>$this->string(255),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull(),
        ]);
        $this->createIndex('idx_audit_tenant_action_created','audit_log',['tenant_id','action','created_at']);
    }

    public function safeDown()
    {
        $this->dropTable('audit_log');
        $this->dropTable('file');
        $this->dropTable('content');
        $this->dropTable('user');
        $this->dropTable('tenant');
    }
}
