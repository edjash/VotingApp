<?php

/**
 * This model represents a User stored in the users table.
 */
class UserModel extends Model
{
    public $table_name = 'users';

    public $properties = array(
        'first_name' => array("type" => "string", "maxlen" => 100, "minlen" => 1),
        'last_name' => array("type" => "string", "maxlen" => 100, "minlen" => 1),
        'email' => array("type" => "email", "minlen" => 3, "unique", "lower"),
        'password' => array("type" => "string", "hash", "lower")
    );

    public function getUser($email, $pass)
    {
        $row = Database::fetchRow($this->table_name, array(
                "email" => trim($email),
                "password"  => sha1(trim($pass))
        ));

        if (!$row) {
            return false;
        }
        return $row;
    }
}
