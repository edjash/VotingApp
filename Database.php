<?php

/**
 * Database class provides static methods for db access
 */
class Database
{
    /** @var object Hold the reference to the PDO object */
    public static $db_conn;

    /**
     * Establishes database connection.
     *
     * @param  string $db_host hostname of mysql server
     * @param  string $db_name name of database
     * @param  string $db_user database username
     * @param  string $db_pass database password
     * @return object Returns the PDO object
     */
    public static function connect($db_host, $db_name, $db_user, $db_pass)
    {
        if (self::$db_conn) {
            return $db_conn;
        }

        $dsn     = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        self::$db_conn = new PDO($dsn, $db_user, $db_pass, $options);
        return self::$db_conn;
    }

    /**
     * Returns a single row from specified table.
     *
     * @param  string $table_name The name of the database table
     * @param  array  $data       The data to use in the WHERE clause
     * @return array|false        Retruns an array of data or false on failure.
     */
    public static function fetchRow($table_name, $data=array())
    {
        if (!count($data)) {
            return false;
        }
        $db_data   = array();
        $db_fields = array();

        foreach ($data as $field => $value) {
            $db_data[':'.$field] = $value;
            $db_fields[] = "`".$field."`=:$field";
        }

        $sql = "SELECT * FROM `$table_name` WHERE ".implode(" AND ", $db_fields)." LIMIT 1";
        $pdo = self::$db_conn->prepare($sql);
        $pdo->execute($db_data);

        return $pdo->fetch();
    }

    /**
     * Fetch an array of table rows
     * @param  string  $sql     The raw SQL query to execute
     * @param  integer $options The PDO fetch options to apply
     * @return array            Array of results.
     */
    public static function fetchArray($sql, $options=null)
    {
        $pdo = self::$db_conn->prepare($sql);
        $pdo->execute();
        return $pdo->fetchAll($options);
    }

    /**
     * Determine if a value exists in the specified
     *
     * @param  string $field      The table field to search
     * @param  string $value      The value to find
     * @param  string $table_name The database table to look in
     * @return bool               returns true if the value exists, false if not.
     */
    public static function valueExists($field, $value, $table_name)
    {
        $sql = "SELECT * FROM `$table_name` WHERE `$field` = ? LIMIT 1";
        $pdo = self::$db_conn->prepare($sql);
        $pdo->execute([$value]);
        if ($pdo->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Insert data into the specified table.
     *
     * @param  array  $data        An array of field => value pairs to insert
     * @param  string $table_name  The name of the table to insert into
     * @return integer             Returns the id of the inserted row
     */
    public static function save($data, $table_name)
    {
        $db_data   = array();
        $db_fields = array();

        foreach ($data as $field => $value) {
            $db_data[':'.$field] = $value;
            $db_fields[] = "`".$field."`";
        }

        $sql = "INSERT INTO `$table_name` (".implode(", ", $db_fields).") VALUES (".implode(",", array_keys($db_data)).")";
        $pdo = self::$db_conn->prepare($sql);
        $pdo->execute($db_data);


        return self::$db_conn->lastInsertId();
    }
}
