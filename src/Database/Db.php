<?php

namespace Src\Database;

use Exception;
use mysqli;
use mysqli_sql_exception;
use PDOException;
use Src\Models\UserModel;
use Src\Models\ModelReport;
use Src\Models\ModelImage;
use Src\Models\ModelAnimalType;
use Src\Models\ModelCoordinates;
use Src\Models\ModelTag;

class Db
{
    /* Db config */
    private $CONFIG;
    /* Db handler */
    private $db;

    /**
     * Constructor.
     */
    public function __construct()
    {
        include 'Config.php';
        $this->CONFIG = $CONFIG;
    }

    /**
     * Close your connection to DB.
     */
    public function __destruct()
    {
    }

    /**
     * Connect to DB.
     */
    public function connect_db()
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
