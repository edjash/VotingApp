<?php

/**
 * This Model represents Constituencies stored in the constituencies table.
 */
class ConstituencyModel extends Model
{
    public $table_name = 'constituencies';

    public $properties = array(
        "name" => array("type" => "string", "minlen" => 1)
    );
}
