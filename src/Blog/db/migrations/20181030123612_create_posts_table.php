<?php


use Phinx\Migration\AbstractMigration;

class CreatePostsTable extends AbstractMigration
{

    public function change(): void
    {
        $this->table('posts')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('content', 'text', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('update_at', 'datetime')
            ->addColumn('create_at', 'datetime')
            ->create();
    }
}
