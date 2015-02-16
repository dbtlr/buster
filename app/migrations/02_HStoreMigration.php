<?php

namespace Migration;

use Knp\Migration\AbstractMigration;
use Silex\Application;

class HStoreMigration extends AbstractMigration
{
    /**
     * @param Application $application
     */
    public function appUp(Application $application)
    {
        /** @var \Doctrine\DBAL\Connection $db */
        $db = $application['db'];

        $db->exec('CREATE EXTENSION hstore');
    }

    /**
     * @return string
     */
    public function getMigrationInfo()
    {
        return 'Add the HStore Extension';
    }
}
