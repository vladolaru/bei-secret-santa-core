<?php
class SecretSantaCoreVlad
{
    protected $fromEmail = null;
    protected $emailTitle = null;
    protected $recommendedExpenses = null;
    protected $users = array();
    protected $sentEmailsAddresses = array();
    protected $pair = array();

    public function setFromEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->fromEmail = $email;
        } else {
            return false;
        }
    }

    public function setEmailTitle($subject)
    {
        $this->emailTitle = $subject;
    }

    public function setRecommendedExpenses($recommended)
    {
        if (is_numeric($recommended)) {
            if ($recommended > 0) {
                $this->recommendedExpenses = $recommended;
            } else {
                echo "You should introduce a positive number.";
            }
        } else {
            echo "Please introduce a number.";
        }
    }

    public function addUsers($users)
    {
        array_push($this->users, array('name' => $users[0], 'email' => $users[1]));

    }

    public function randomizeEmails($users)
    {
        $first = 0;
        $last = count($this->users) - 1;
        return rand($first, $last);
    }

    protected function checkMultipleEmail()
    {
        
    }

    public function getSentEmailsAddresses()
    {
        return $this->sentEmailsAddresses;
    }

    public function checkUser()
    {
        if ($this->fromEmail == '' || $this->recommendedExpenses == 0 || $this->checkMultipleEmail() == false) {
            return false;
        } else
            return true;
    }

    public function goRudolph()
    {

    }
}





