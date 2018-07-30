<?php

class SecretSantaCoreCosmin
{
    protected $fromEmail = '';
    protected $emailTitle = 'NoTitle';
    protected $recommendedExpenses = 0;
    protected $users = array();
    protected $userEmail = '';


    public function setFromEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->fromEmail = $email;
            return 1;
        } else
            //echo "The email from which the emails will be sent is not a valid one!";
        return 0;
    }

    public function setRecommendedExpenses($sum)
    {
        if (is_numeric($sum) && $sum>0) {
            $this->recommendedExpenses = $sum;
            return 1;
        } else
            return 0;
    }

    public function setEmailTitle($title)
    {
        $this->emailTitle = $title;
        return 1;
    }

    public function getSentEmailAddresses($users)
    {
        foreach ($users as $key => $user) {
            return $user['email'];
        }
    }

    public function addUsers($users)
    {
        $users = array ('users' => array ('name', 'email'));
    }

    public function randomizeUsers($users)
    {
            $firstUser = 0;
            $lastUser = count($users) - 1;
            return rand($firstUser, $lastUser);
    }

    public function userSantaCheck()
    {

        if (count($this->users) <= 2 && count($this->users) >= 0) {
            //echo "Very few users added, result is obvious";
            return 0;
        }
        if()




        $msg='Dear' . ' ' . $user1 . ', ' . ' the person you will have to give a gift to is' . ' ' . $user2 . ', ' .
            'and the gift value is' . ' ' . $recommendedExpenses . ' USD';
        echo $msg;

    }

    public function goRudolph()
    {
        //sa nu uitam sa verificam ca avem toate valorile valide
        if($this->userSantaCheck()==0)
        {
            echo "You missed something!";
        }
    }



    }


}

//de gandit algoritmul in care punem in array-ul nostru
//cum luam datele specifice, cum ar fi adresele la care s-au trimis emailurile date de noi
//algoritmul care verifica validitatea userilor in momentul imperecherii si mesajul necesar
