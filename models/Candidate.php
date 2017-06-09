<?php

/**
 * This model represents election Candidates in the candidates table
 */
class CandidateModel extends Model
{
    public $table_name = 'candidates';

    public $properties = array(
        "name" => array("type" => "string", "minlen" => 1),
        "party" => array("type" => "string", "minlen" => 1),
        "image_url" => array("type" => "string", "minlen" => 1),
        "constituency_id" => array("type" => "number")
    );

    public function getCandidate($id)
    {
        $row = Database::fetchRow($this->table_name, array(
                "id" => $id
        ));
        if (!$row) {
            return false;
        }
        return $row;
    }
}
