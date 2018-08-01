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
        // numarul introdus trebuie sa fie pozitiv si mai mare decat 0
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

    protected function checkMultipleEmail()
    {
        // functia verifica sa nu existe email-uri introduse gresit sau de doua ori
        for ($i = 0; $i < count($this->users); $i++) {
            if (filter_var($this->users[$i]['email'], FILTER_VALIDATE_EMAIL) == false) {
                return false;
            }
        }
        for ($i = 0; $i < count($this->users) - 2; $i++) {
            for ($n = $i + 1; $n < count($this->users) - 1; $n++) {
                if ($this->users[$i]['email'] == $this->users[$n]['email']) {
                    return false;
                }
            }
        }
        return true;
    }

    public function randomize()
    {
        // impartim aleator utilizatorii introdusi
        $first = 0;
        $last = count($this->users) - 1;
        return rand($first, $last);
    }

    protected function pairUsers()
    {
        // creez un array in care se vor regasi userii; daca userul este nou indrodus, acesta va avea valoarea 1, daca deja primeste cadou, valoarea 0
        // ru = randomized users
        $ru = array();
        for ($i = 0; $i < count($this->users); $i++) {
            $ru[$i] = 0;
        }

        // fiecarui user ii va fi atribuita o pereche, luand in calcul ca destinatarul sa nu fie ales deja
        for ($i = 0; $i < count($this->users); $i++) {
            $value = $this->randomize();
            while ($ru[$value] != 0 || $value == $i) {
                $value = $this->randomize();
            }

            $this->pair[$i] = $value;
            $ru[$value] = 1;
        }
    }

    public function getSentEmailsAddresses()
    {
        return $this->sentEmailsAddresses;
    }

    public function checkUser()
    {
        if ('' == $this->fromEmail || 0 == $this->recommendedExpenses || false == $this->checkMultipleEmail()) {
            return false;
        } else {
	        return true;
        }
    }

    public function goRudolph()
    {
        if ($this->checkUser() == true) {
            $this->pairUsers();
        }
        
    }

}









