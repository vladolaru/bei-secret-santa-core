<?php

class SecretSantaCore
{
    protected $fromEmail = '';
    protected $emailTitle = '';
    protected $recommendedExpenses = 0;
    protected $users = array();
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

    public function getUsers($users)
    {

    }

    public function getSentEmailAddresses()
    {
        getUsers();
        foreach ($users as $key => $user) {
            return $user['email'];
        }
    }

    public function addUsers()
    {
        //luam userii si ii punem intr-un array
    }

    public function randomizeUsers($users)
    {
        //verificam validitatea array-ului nostru, si anume sa contina cel putin 3 useri
        if (count($users) > 2) {
            $firstuserkey = 0;
            $lastuserkey = count($users) - 1;
            rand($firstUser, $lastUser);
        }
        if (count($users) <= 2 && count($users) > 0) {
            echo "Very few users added, result is obvious";
        } else
            echo "No users were written!";


    }

    public function userSantaCheck()
    {
        //doar o potentiala metoda de a rezolva problema gasirii a doi user care au flag=1,
        //si anume sunt pasibili de a primi cadou; mai e mult de modificat
        foreach ($users as key=>$user){
        randomizeUsers($users);
        $pairedUsers=count($users)/2;

        //la fiecare pereche gasita, scadem $pairedUsers pana ajungem la 0.
        // Cand ajungem, returnam flag=1 pentru a spune ca userSantaCheck si-a terminat treaba

        $msg='Dear' . ' ' . $user1 . ', ' . ' the person you will have to give a gift to is' . ' ' . $user2 . ', ' .
            'and the gift value is' . ' ' . $recommendedExpenses . ' USD';
        echo $msg;

    }

    public function goRudolph()
    {
        userSantaCheck();
        if(flag=1)
        {
            mail($fromEmail,$emailTitle,$msg);
        }
        else
            userSantaCheck();
    }



    }


}

//de gandit algoritmul in care punem in array-ul nostru
//cum luam datele specifice, cum ar fi adresele la care s-au trimis emailurile date de noi
//algoritmul care verifica validitatea userilor in momentul imperecherii si mesajul necesar
