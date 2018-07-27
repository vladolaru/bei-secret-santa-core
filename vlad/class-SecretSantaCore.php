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
            $this->fromEmail = $email;
        }
        else {
            echo "The e-mail $email address is invaild!";
        }
    }
    public function setEmailTitle ($subject)
    {

    }
    public function setRecommendedExpenses ($recommended)
    {
        if (is_numeric ($recommended))
        {
            $this->recommendedExpenses = $recommended;
        }
    }


}

$santa = new SecretSantaCore();
$santa->setMailFrom('test@test.com');
$santa->setMailTitle();
$santa->setRecommendedExpenses(); //trebuie sa fie float!!
$santa->setUsers();
$santa->run();
$santa->getSentEmailsAddresses();



