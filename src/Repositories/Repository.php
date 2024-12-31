<?php

namespace Src\Repositories;

use mysqli_sql_exception;
use Src\Database\Config;
use mysqli;

/**
 * Class Repository
 *
 * This class provides methods for connecting to the database.
 */
class Repository
{
    protected $db;
    private mixed $CONFIG;

    /**
     * Repository constructor.
     *
     * Initializes the Repository object and connects to the database.
     */
    public function __construct()
    {
        include __DIR__ . '/../Database/Config.php';
        $this->CONFIG = $CONFIG;
        $this->connectDB();
    }

    /**
     * Connects to the database.
     *
     * This method attempts to establish a connection to the database using the configuration settings.
     * If the connection fails, it triggers an error.
     */
    private function connectDB()
    {
        try {
            $this->db = new mysqli(
                $this->CONFIG['servername'],
                $this->CONFIG['username'],
                $this->CONFIG['password'],
                $this->CONFIG['db']
            );

            // if (!$this->db) {
            //     echo 'Not connected to DB';
            // } else {
            //     echo 'Successfully connected to DB';
            // }
        } catch (mysqli_sql_exception $e) {
            trigger_error('Could not connect to database: ' . $e->getMessage(), E_USER_ERROR);
        }
    }
}