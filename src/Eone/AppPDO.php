<?php

declare(strict_types=1);

namespace Eone;

/**
 * Create a custom PDO connection based on the db login information in the environment.
 */
class AppPDO extends \PDO
{
    public function __construct()
    {
        $dsn = 'mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME');
        parent::__construct($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
    }
}
