<?php

/**
 * This model represents a Vote stored in the votes table.
 */
class VoteModel extends Model
{
    public $table_name = 'votes';

    public $properties = array(
        "user_id" => array("type" => "int"),
        "candidate_id" => array("type" => "int"),
        "abstained" => array("type" => "int")
    );

    public function getUserVote($user_id)
    {
        $row = Database::fetchRow($this->table_name, array("user_id" => $user_id));
        return $row;
    }
}
