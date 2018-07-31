<?php

class SecretSantaCoreCosmin
{
    protected $fromEmail = '';
    protected $emailTitle = 'NoTitle';
    protected $recommendedExpenses = 0;
    protected $users = array();
    protected $sentEmailAddresses = array();
    protected $pairing = array();//vector de frecventa, 1 inseamna ca a fost luat,0 ca e valid


    public function setFromEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->fromEmail = $email;
        } else
            //echo "The email from which the emails will be sent is not a valid one!";
            return false;
    }

    public function setRecommendedExpenses($sum)
    {
        if (is_numeric($sum) && $sum > 0) {
            $this->recommendedExpenses = $sum;
        } else
            $this->recommendedExpenses = 0; //return false;
    }

    public function setEmailTitle($title)
    {
        $this->emailTitle = $title;
    }

    public function getSentEmailAddresses()
    {
        return $this->sentEmailAddresses;
    }

!!!!!!!!!!!!!!!!!!!!!!!!!!public function addUsers($users)
{
    array_push($this->users, array('name' => $user[0], 'email' => $user[1]));

}

    public function randomizeUsers()
    {
        $firstUser = 0;
        $lastUser = count($this->users) - 1;
        return rand($firstUser, $lastUser);
    }

    public function userSantaCheck()
    {
        if (count($this->users) <= 2 && count($this->users) >= 0) {
            //echo "Very few users added, result is obvious";
            return false;
        }
        if ($this->fromEmail == '' || $this->recommendedExpenses == 0 || $this->doubleCheckUsersEmail() == false) {
            return false;
        } else
            return true;
    }

    protected function doPairing()
    {
        //initializam matricea cu 0
        for ($i = 0; $i < count($this->users); $i++) {
            $this->pairing[$i] = 0;
        }

        //pentru fiecare user inscris, ii gasim o pereche, conditia fiind ca destinatarul sa nu fie ales deja
        for ($i = 0; $i < count($this->users); $i++) {
            $a = $this->randomizeUsers();

            while ($this->pairing[$a] == 1) {
                $a = $this->randomizeUsers();
            }
            $this->pairing[$a] = 1;
        }
    }


//verificam ca 2 useri sa nu aiba acelasi e-mail
    protected function doubleCheckUsersEmail()
    {
        for ($i = 0; $i < count($this->users); $i++) {
            if (filter_var($this->users[$i]['email'], FILTER_VALIDATE_EMAIL) == false) {
                return false;
            }
        }

        for ($i = 0; $i < count($this->users) - 2; $i++) {
            for ($j = $i + 1; $j < count($this->users) - 1; $j++) {
                if ($this->users[$i]['email'] == $this->users[$j]['email']) {
                    return false;
                }
            }
        }
        return true;
    }

    public function goRudolph()
    {
        if ($this->userSantaCheck() == true) {
            foreach ($this->users as $user)
                array_push($this->getSentEmailAddresses(), $this->users['email']);
            ////$this->pairing($this->users);
        } else
            echo "Not all the information written is correct, please retry!";
    }


}


