<?php
class SecretSantaCoreVlad
{
    protected $FromEmail = null;
    protected $EmailTitle = null;
    protected $recommendedExpenses = null;
    protected $users = array();
    protected $sentEmails = array();
    protected $pair = array();

    public function setFromEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->FromEmail = $email;
        } else {
            echo "The e-mail $email address is invaild!";
        }
    }

    public function setEmailTitle($subject)
    {
        $this->EmailTitle = $subject;
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
        array_push($this->users, array('name' => $user[0], 'email' => $user[1]));
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

    public function getSentEmailAddresses()
    {
        return $this->sentEmails;
    }

    public function checkUser()
    {
        if ($this->FromEmail == '' || $this->recommendedExpenses == 0) {
            return false;
        } else
            return true;
    }

    public function goRudolph(){}
}





