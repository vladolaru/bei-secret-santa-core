<?php

class SecretSantaCore
{
    protected $fromEmail = '';
    protected $emailTitle = '';
    protected $recommendedExpenses = 0;
    protected $user = '';
    protected $userEmail = '';


    public function setFromEmail($email)
    {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->fromEmail = $email;
        } else
            echo "The email from which the emails will be sent is not a valid one!";
    }

    public function setRecommendedExpenses($sum)
    {
        if (is_numeric($sum)) {
            $this->recommendedExpenses = $sum;
        } else
            echo "The value given to recommended sum is not a numeric one!";
    }

    public function setEmailTitle($title)
    {
        $this->emailTitle = $title;
    }

    public function goRudolph()
    {

    }

    public function getSentEmailAdresses()
    {

    }


}

