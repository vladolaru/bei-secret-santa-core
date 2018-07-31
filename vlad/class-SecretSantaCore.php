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

        var_dump( $this->users );

    }

    public function randomize($users)
    {
        $first = 0;
        $last = count($this->users) - 1;
        return rand($first, $last);
    }

    protected function checkMultipleEmail()
    {
        for ($m = 0; $m < count($this->users); $m++) {
            if (filter_var($this->users[$m]['email'], FILTER_VALIDATE_EMAIL) == false) {
                return false;
            }
        }
        for ($m = 0; $m < count($this->users) - 2; $m++) {
            for ($n = $m + 1; $n < count($this->users) - 1; $n++) {
                if ($this->users[$m]['email'] == $this->users[$n]['email']) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function pairUsers()
    {
        $ru = array();
        for ($i = 0; $i < count($this->users); $i++) {
            $ru[$i] = 0;
        }
        for ($i = 0; $i < count($this->users); $i++) {
            $v = $this->randomize();
            while ($ru[$v] != 0 || $v == $i) {
                $v = $this->randomize();
            }
            $ru[$v] = 1;
        }
    }

    public function getSentEmailsAddresses()
    {
        return $this->sentEmailsAddresses;
    }

    public function checkUser()
    {
        if ($this->fromEmail == '' || $this->recommendedExpenses == 0 || $this->checkMultipleEmail() == false) {
            return false;
        } else {
	        return true;
        }
    }

    public function goRudolph()
    {
        if ($this->checkUser() == true) {
            foreach ($this->users as $user) {
                array_push($this->getSentEmailsAddresses(), $this->users['email']);
            }


            $this->pairUsers();

            for ($v = 0; $v < count($this->users); $v++) {
                $msg = $this->users[$v]['name'] . ',' . "\r\n" . "\r\n" . "The Christmas is coming and for the Secret Santa event you have to buy a present for " . $this->users[$this->randomize[$v]]['name'] . " , his/her email is "
                    . $this->users[$this->randomize[$v]]['email'] . ". Also, the recommended value of the gift is " . $this->recommendedExpenses
                    . ". Merry Christmas!";
                mail($this->users[$v]['email'], $this->emailTitle, $msg, "From: " . $this->fromEmail);
            }

        }

            else
            echo "Please check again!!";
    }

}







