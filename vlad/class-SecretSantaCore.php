<?php
class SecretSantaCore
{
    protected $FromEmail = null;
    protected $EmailTitle = null;
    protected $recommendedExpenses = null;
    protected $users = array();
    protected $sentEmails = array ();

    public function setFromEmail ($email)
    {
        if (filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            $this-> FromEmail = $email;
        }
        else {
            echo "The e-mail $email address is invaild!";
        }
    }
    public function setEmailTitle ($subject) {
        foreach ($subject as $type ){
            if( !(  ( $type >= 'A' && $type <= 'Z' ) || ( $type >= 'a' && $type <='z' ) ||
                ( $type >= '0' && $type <= '9' ) || ( $type == ' ' || $type == '!' || $type == '.' ) ) ) {
                echo "Wrong subject";
                break;
            }
        }
        $this->EmailTitle = $subject;
    }
    public function setRecommendedExpenses ($recommended)
    {
        if (is_numeric($recommended))
        {
            if ($recommended > 0)
            {
                $this->recommendedExpenses = $recommended;
            }
            else {
                echo "You should introduce a positive number.";
            }
        }
        else {
            echo "Please introduce a number.";
        }
    }
    public function addUsers (array)
    {
        $name=['name'];
        $email=['email'];
        $users= array('name' => $name, 'email' => $email);
    }
    public function goRudolph () {}
    public function getSentEmailsAddresses (){}

}






