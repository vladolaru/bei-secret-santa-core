<?php

class SecretSantaCoreCosmin
{
    protected $fromEmail = '';
    protected $emailTitle = 'NoTitle';
    protected $recommendedExpenses = 0;
    protected $users = array();
    protected $sentEmailAddresses = array();
    protected $pairing = array();


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

    public function addUsers($users)
    {
        array_push($this->users, array('name' => $user[0], 'email' => $user[1]));

    }

    public function randomizeUsers($users)
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
        if ($this->fromEmail == '' || $this->recommendedExpenses == 0) {
            return false;
        } else
            return true;
    }

    //folosind functia random, punem pe toti cei care au fost deja selectati sa primeasca un cadou,
    //sa nu fie realesi
!!!!protected function doPairing()
{


}

//verificam ca 2 useri sa nu aiba acelasi e-mail
!!!!protected function doubleCheckUsersEmail()
{

}

    public function goRudolph()
    {
        if ($this->userSantaCheck() == true) {
            foreach ($this->users as $user)
                array_push($this->getSentEmailAddresses(), $this->users['email']);
            ////
            pairing($this->users);
        } else
            echo "Not all the information written is correct, please retry!";
    }


}


