<?php

class SecretSantaCoreCosmin
{
    protected $fromEmail = '';
    protected $emailTitle = '';
    protected $recommendedExpenses = 0;
    protected $users = array();
    protected $sentEmailsAddresses = array();
    protected $pairing = array();


    public function setFromEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->fromEmail = $email;
        } else {
            return false;
        }
    }

    public function setRecommendedExpenses($sum)
    {
        if (is_numeric($sum) && $sum > 0) {
            $this->recommendedExpenses = $sum;
        }

        return false;
    }

    public function setEmailTitle($title)
    {
        if ('' == $title) {
            $this->emailTitle = 'NoTitle';
        }
        else
        {
            $this->emailTitle = $title;
        }

    }

    public function getSentEmailsAddresses()
    {
        return $this->sentEmailsAddresses;
    }

    public function addUsers($users)
    {
        if (empty($users) || !is_array($users)) {
            return false;
        }

        foreach ($users as $user) {

            array_push($this->users, array(
                    'name' => $user[0],
                    'email' => $user[1],
                )
            );
        }

    }

    protected function randomizeUsers()
    {
        $firstUser = 0;
        $lastUser = count($this->users) - 1;

        return rand($firstUser, $lastUser);
    }

    public function userSantaCheck()
    {
        if (count($this->users) <= 2 && count($this->users) >= 0) {
            return false;
        }
        if ('' == $this->fromEmail || 0 == $this->recommendedExpenses || false == $this->doubleCheckUsersEmail()) {
            return false;
        } else
            return true;
    }

    protected function doPairing()
    {
        //initializam cu 0 array-ul care va retine userii care au primit deja cadou; in caz de primesc, valoarea este 1;
        $frequency = array();
        for ($i = 0; $i < count($this->users); $i++) {
            $frequency[$i] = 0;
        }


        //pentru fiecare user inscris, ii gasim o pereche, conditia fiind ca destinatarul sa nu fie ales deja
        for ($i = 0; $i < count($this->users); $i++) {
            $a = $this->randomizeUsers();

            while ($frequency[$a] != 0 || $a == $i) {
                $a = $this->randomizeUsers();
            }
            $this->pairing[$i] = $a;
            $frequency[$a] = 1;
        }
    }


//verificam ca 2 useri sa nu aiba acelasi e-mail sau ca e-mailurile lor sa fie valide
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
        //adaugam userii doriti

        if ($this->userSantaCheck() == true) {

            $this->doPairing();

            //trimitem mailurile la fiecare email cu cheia i, cu toate datele aferente
            for ($i = 0; $i < count($this->users); $i++) {

                $msg = 'Dear ' . $this->users[$i]['name'] . ',' . "\r\n" . "\r\n" . 'The special person that you have to buy a present for, for the occasion of the Secret Santa Event, is ' . $this->users[$this->pairing[$i]]['name'] . ' with the email '
                    . $this->users[$this->pairing[$i]]['email'] . ' and the recommended value of the present is ' . $this->recommendedExpenses
                    . '. Have a jolly day!';

                if (mail($this->users[$i]['email'], $this->emailTitle, $msg, 'From: ' . $this->fromEmail)) {
                    array_push($this->sentEmailsAddresses, $this->users[$i]['email']);
                }
            }

        } else
            echo "Not all the information written is correct.";
    }
}


