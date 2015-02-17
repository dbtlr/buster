<?php

namespace Migration;

use Dbtlr\MigrationProvider\Migration\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version1424188693CreateHstoreMigration extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $app = $this->getApplication();

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $app['db'];
        $connection->exec('CREATE EXTENSION hstore');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $app = $this->getApplication();

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $app['db'];
        $connection->exec('DROP EXTENSION hstore');
    }

    /**
     * @return string
     */
    public function getMigrationInfo()
    {
        return 'Version 1424188693 - Create HStore';
    }
}
