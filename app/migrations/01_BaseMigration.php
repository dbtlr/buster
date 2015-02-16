<?php

namespace Migration;

use Knp\Migration\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class BaseMigration extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function schemaUp(Schema $schema)
    {
        $table = $schema->createTable('tracking');
        $table->addColumn('id', 'integer', array(
            'unsigned'      => true,
            'autoincrement' => true
        ));
        $table->setPrimaryKey(array('id'));

        $table->addColumn('uniqId', 'string', array('length' => 36, 'notnull' => false));
        $table->addColumn('requestTime', 'datetime');
        $table->addColumn('type', 'string', array('length' => 20));
        $table->addColumn('domain', 'string');
        $table->addColumn('referrer', 'string', array('nullable' => true));
        $table->addColumn('referrerDomain', 'string', array('notnull' => false));
        $table->addColumn('remoteAddr', 'string', array('length' => 15));
        $table->addColumn('identity', 'string', array('notnull' => false));
        $table->addColumn('url', 'string');
        $table->addColumn('userAgent', 'string');
        $table->addColumn('meta', 'hstore', array('notnull' => false));
    }

    public function getMigrationInfo()
    {
        return 'Added tracking table';
    }
}
