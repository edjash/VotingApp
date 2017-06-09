<?php

/**
 * This class handles account registration and login.
 */
class Account extends Controller
{
    public $require_auth = false;

    public function register()
    {
        if ($this->isPost()) {
            return $this->registerUser();
        }
    }

    public function login()
    {
        if ($this->isPost()) {
            return $this->doLogin();
        }
    }

    /**
     * Checks wether or not an email address already exists in the users table.
     *
     * This method is called as the user types an email address on the reg form.
     */
    public function validate()
    {
        $email = trim($_GET['email']);
        if (Database::valueExists('email', $email, 'users')) {
            header('HTTP/1.0 500 This account already exists');
            die();
        }
        send_json(true);
    }

    public function registerUser()
    {
        $user = $this->getModel('User');

        if (!$user->save($_POST, 'Users')) {
            send_json(false, array("errors" => $user->errors));
        }
        $this->doLogin();
    }

    /**
     * Logs the user in and sets up the session.
     */
    public function doLogin()
    {
        $email = (isset($_POST['email'])) ? $_POST['email'] : false;
        $pass  = (isset($_POST['password'])) ? $_POST['password'] : false;

        if (!$email || !$pass) {
            Router::send404();
        }

        $user = $this->getModel('User')->getUser($email, $pass);
        if (!$user) {
            Router::send404();
        }

        $vote = $this->getModel('Vote')->getUserVote($user['id']);

        $user['vote'] = ($vote) ? $vote['candidate_id'] : false;
        if ($vote['abstained']) {
            $user['vote'] = 'abstained';
        }
        if ($vote && !$vote['abstained']) {
            $candidate = $this->getModel('Candidate')->getCandidate($vote['candidate_id']);
            $user['candidate'] = $candidate;
        }

        unset($user['password']);
        $_SESSION['user'] = $user;

        send_json(true, array(
            "user" => $user
        ));
    }
}
