<?php

class SecretSantaCore
{
    protected $fromEmail = '';
    protected $emailTitle = 'NoTitle';
    protected $recommendedExpenses = 0;
    protected $users = array();
    protected $sentEmailsAddresses = array();
    protected $pairing = array();//vector care retine cine trimite si persoana careia ii va trimite
                                    // $a inseamna ca a fost luat, -1 ca e valid pentru a fi luat


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

    public function getSentEmailsAddresses()
    {
        return $this->sentEmailsAddresses;
    }

    public function addUsers( $users )
{
    foreach ($users as $user) {

        array_push($this->users, array(
                'name' => $user[0],
                'email' => $user[1]
            )
        );
    }

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
        //initializam vectorul frecventa
        $frequency=array();
        for ($i = 0; $i < count($this->users); $i++)
        {
            $frequency[$i]=0;
        }

        //initializam matricea cu -1
        for ($i = 0; $i < count($this->users); $i++) {
            $this->pairing[$i] = -1;
        }

        //pentru fiecare user inscris, ii gasim o pereche, conditia fiind ca destinatarul sa nu fie ales deja
        for ($i = 0; $i < count($this->users); $i++) {
            $a = $this->randomizeUsers();

            while ($frequency[$a] != 0 || $a == $i) {
                $a = $this->randomizeUsers();
            }
            $this->pairing[$i] = $a;
            $frequency[$a]=1;
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
            foreach ($this->users as $user)//push de emailurile la care trimitem
            {
                array_push($this->sentEmailsAddresses, $user['email']);
            }
            $this->doPairing();

            //trimitem mailurile la fiecare email cu cheia i, cu toate datele aferente
            for ($i = 0; $i < count($this->users); $i++) {

                $msg = 'Dear ' . $this->users[$i]['name'].',' . "\r\n". "\r\n". 'The special person that you have to buy a present for, for the occasion of the Secret Santa Event, is ' . $this->users[$this->pairing[$i]]['name'] . ' with the email '
                        . $this->users[$this->pairing[$i]]['email'] . ' and the recommended value of the present is ' . $this->recommendedExpenses
                        . '. Have a jolly day!';

                mail($this->users[$i]['email'], $this->emailTitle, $msg, 'From: ' . $this->fromEmail);
            }

        } else
            echo "Not all the information written is correct, please retry!";
    }


}


