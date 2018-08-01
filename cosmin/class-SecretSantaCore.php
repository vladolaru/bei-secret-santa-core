<?php

class SecretSantaCoreCosmin
{
    /**
     * The email from which we will send the messages.
     *
     * @var null | string
     */
    protected $fromEmail = '';

    /**
     * The title of the emails.
     *
     * @var null | string
     */
    protected $emailTitle = '';

    /**
     * The required sum for the presents.
     *
     * @var null | int
     */
    protected $recommendedExpenses = 0;

    /**
     * The array in which we will add the persons that will participate for Secret Santa.
     *
     * @var array
     */
    protected $users = array();

    /**
     * The array in which we will add the email addresses to which mails were sent.
     *
     * @var array
     */
    protected $sentEmailsAddresses = array();

    /**
     * The array that will be used to know who gives a present to who.
     *
     * The sender is the $i key value and the receiver will be the one in the pairing[$i] slot.
     *
     * @var array
     */
    protected $pairing = array();

    /**
     * Sets the mail from which the messages will be sent.
     *
     * @var null | string
     * @return bool False if the mail fails the validation filter.
     *              Otherwise, it returns true.
     */
    public function setFromEmail($email)
    {
        if (null == filter_var($email, FILTER_VALIDATE_EMAIL) || false == filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            $this->fromEmail = $email;
            return true;
        }
    }

    /**
     * Sets the recommendedExpenses attribute.
     *
     * @param $sum int | float
     * @return bool True if the value is a numeric one or if it's bigger than 0.
     *              Otherwise, it returns false.
     */
    public function setRecommendedExpenses($sum)
    {
        if (is_numeric($sum) && $sum > 0) {
            $this->recommendedExpenses = $sum;
            return true;
        }

        return false;
    }

    /**
     * Sets the title for all of the emails.
     *
     * In case the given string is empty, the title will be 'NoTitle'.
     * Otherwise, the title will be the given string.
     *
     * @param $title null | string
     */
    public function setEmailTitle($title)
    {
        $title = trim($title);
        if ('' == $title) {
            $this->emailTitle = 'NoTitle';
        } else {
            $this->emailTitle = $title;
        }

    }

    /**
     * Returns the array that contains the email addresses to which the notifications were sent.
     *
     * @return array
     */
    public function getSentEmailsAddresses()
    {
        return $this->sentEmailsAddresses;
    }

    /**
     * Adds all the given users in an array.
     *
     *
     * @param $users array
     * @return bool False if the array given is empty or if the value given is not an array.
     *              Otherwise, it returns true.
     */
    //add users sa functioneze sau sa specifice

    public function addUsers($users)
    {
        if (empty($users) || !is_array($users)) {
            return false;
        }

        foreach ($users as $user) {

            if (is_null($user[0]) ||
                is_null($user[1]) ||
                false == filter_var($user[1], FILTER_VALIDATE_EMAIL) ||
                '' == trim($user[0])) {
                continue;
            }
            array_push($this->users, array(
                    'name' => $user[0],
                    'email' => $user[1],
                )
            );
        }
        return true;
    }

    /**
     * Selects, from a given range, a random value, which is needed to do the pairing of the users.
     *
     * @return int
     */
    protected function randomizeUsersKey()
    {
        $firstUser = 0;
        $lastUser = count($this->users) - 1;

        return rand($firstUser, $lastUser);
    }

    /**
     * Checks if all the functions are properly used, so that there will be no unexpected results after we Run the code.
     *
     * @return bool False if there are less than 3 users or no email address from which we will send emails,
     *              or if the required sum for the gifts is equal to 0, or if the emails validation function fails.
     *              Otherwise, it returns true.
     */
    public function attributesCheck()
    {
        if (count($this->users) < 3 ||
            '' == $this->fromEmail ||
            0 == $this->recommendedExpenses ||
            false == $this->validateUsersEmails()) {

            return false;
        } else {
            return true;
        }
    }

    /**
     * It initializes a frequency array (with the 0 value) that will help in knowing which user already received a present.
     *
     * Does the pairings and puts the values in the $pairing array.
     *
     * @see $pairing - to understand what the array represents.
     */
    protected function doPairing()
    {
        $frequency = array();
        for ($i = 0; $i < count($this->users); $i++) {
            $frequency[$i] = 0;
        }

        for ($i = 0; $i < count($this->users); $i++) {
            $randomUser = $this->randomizeUsersKey();

            while (0 != $frequency[$randomUser] || $randomUser == $i) {
                $randomUser = $this->randomizeUsersKey();
            }
            $this->pairing[$i] = $randomUser;
            $frequency[$randomUser] = 1;
        }
    }

    /**
     * Checks if there is an email address wrongly written, or if there are 2 emails that are the same.
     *
     *
     * @return bool False if there is an invalid email or if there are users with the same email.
     *              Otherwise returns true.
     */
    protected function validateUsersEmails()
    {
        for ($i = 0; $i < count($this->users); $i++) {
            if (false == filter_var($this->users[$i]['email'], FILTER_VALIDATE_EMAIL)) {
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

    /**
     * Checks if every required function was used properly, does the pairing between the given users.
     *
     * Sends the emails to the users, and, one by one, if the email is accepted for delivery, it is added to the sentEmailAddresses attribute.
     *
     * @see doPairing() - to understand how the pairings are done and how/to who are the emails sent.
     */
    public function goRudolph()
    {

        if ($this->attributesCheck() == true) {

            $this->doPairing();

            for ($i = 0; $i < count($this->users); $i++) {

                $msg = 'Dear ' . $this->users[$i]['name'] . ',' . "\r\n" . "\r\n" . 'The special person that you have to buy a present for, for the occasion of the Secret Santa Event, is ' . $this->users[$this->pairing[$i]]['name'] . ' with the email '
                    . $this->users[$this->pairing[$i]]['email'] . ' and the recommended value of the present is ' . $this->recommendedExpenses
                    . '. Have a jolly day!';

                if (mail($this->users[$i]['email'], $this->emailTitle, $msg, 'From: ' . $this->fromEmail)) {
                    array_push($this->sentEmailsAddresses, $this->users[$i]['email']);
                }
            }

        } else
            echo "Not all the information written is correct.  ";
    }
}


