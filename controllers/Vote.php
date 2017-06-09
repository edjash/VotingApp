<?php

/**
 * This controller handles vote submission.
 */
class Vote extends Controller
{
    public function index()
    {
        if ($this->isPost()) {
            return $this->saveVote();
        }
    }

    public function saveVote()
    {
        $model = $this->getModel('Vote');
        $r     = $model->save(array(
            "user_id"       => $_SESSION['user']['id'],
            "candidate_id" => ($_POST['candidate_id'] == 'abstain') ? 0 : $_POST['candidate_id'],
            "abstained"       => ($_POST['candidate_id'] == 'abstain') ? 1 : 0
        ));

        if (!$r) {
            send_json(false, $model->errors);
        }

				//The vote has been saved, so we adjust the session variable and reload the page
        $user = $_SESSION['user'];
        $vote = $this->getModel('Vote')->fetchSingle($r);
        $user['vote'] = ($vote) ? $vote['candidate_id'] : false;
        if ($vote['abstained']) {
            $user['vote'] = 'abstained';
        }
        if ($vote && !$vote['abstained']) {
            $candidate = $this->getModel('Candidate')->getCandidate($vote['candidate_id']);
            $user['candidate'] = $candidate;
        }
        $_SESSION['user'] = $user;

        send_json(true);
    }
}
