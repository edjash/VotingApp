<?php

/**
 * This is the controller that handles the main homepage
 */
class Index extends Controller
{
    public $require_auth = false; //this page does not require the user to be authenticated

    public function index()
    {
        if (isset($_GET['logout'])) {
            @session_destroy();
            header('Location: '.BASE_URL);
            exit;
        }

        //Generate a list of constituencies and candidaties
        $constituencies = $this->getModel('Constituency')->fetchAll();
        $candidates    = $this->getModel('Candidate')->fetchAll();

        $data = array();
        foreach ($candidates as $id => $candidate) {
            $constituencies[$candidate['constituency_id']]['candidates'][$id] = $candidate;
        }

        //Allow the view file to access it
        $this->viewVars['constituencies'] = $constituencies;
    }
}
