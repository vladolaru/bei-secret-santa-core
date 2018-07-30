<?php

class SecretSantaCoreCosmin
{
    protected $fromEmail = '';
    protected $emailTitle = 'NoTitle';
    protected $recommendedExpenses = 0;
    protected $users = array();
    protected $sentEmailAddresses=array();


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
        if (is_numeric($sum) && $sum>0) {
            $this->recommendedExpenses = $sum;
        } else
            $this->recommendedExpenses = 0; //return false;
    }

    public function setEmailTitle($title)
    {
        $this->emailTitle = $title;
        return 1;
    }

    public function getSentEmailAddresses()
    {
         return $this->sentEmailAddresses;
    }

    !!!! public function addUsers($users)
    {
        array_push($this->users, array( 'name' => , 'email' => $email));

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
        if($this->fromEmail=='' || $this->recommendedExpenses==0)
        {
            return false;
        }
        else
            return true;
    }

    public function goRudolph()
    {
        if($this->userSantaCheck()===true)
        {
            array_push($this->getSentEmailAddresses(), $this->users['email']);
        }
        else
            echo "Not all the information written is correct, please retry!";
    }





}


