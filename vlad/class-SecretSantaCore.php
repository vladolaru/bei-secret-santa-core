<?php

/**
 * @author Vlad Popa <vladpopa63@gmail.com>
 */

class SecretSantaCoreVlad {
	/**
	 * This class can be used for any Secret Santa event.
	 */
	protected $fromEmail = null;
	protected $emailTitle = null;
	protected $recommendedExpenses = null;
	protected $users = array();
	protected $sentEmailsAddresses = array();

	/**
	 * Here you can set the initial e-mail address from which you will send messages to everyone involved in this action.
	 *
	 * @param $email
	 *
	 * @return bool
	 */
	public function setEmailFrom( $email ) {
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$this->fromEmail = $email;
		} else {
			return false;
		}
	}

	/**
	 * This function sets an e-mail subject.
	 *
	 * @param $subject
	 */
	public function setEmailTitle( $subject ) {
		$this->emailTitle = $subject;
	}

	/**
	 * The function picks up a number that has to be positive.
	 *
	 * @param $recommended
	 */
	public function setRecommendedExpenses( $recommended ) {
		if ( is_numeric( $recommended ) ) {
			if ( $recommended > 0 ) {
				$this->recommendedExpenses = $recommended;
			}
		}
	}

	/**
	 * This function will create an array that include names and e-mails addresses of the participants. Also, it will be
	 * checked if the e-mail address is correct.
	 *
	 * @param $users
	 *
	 * @return bool
	 */
	public function addUsers( $users )
	{
		$count_users = 0;

		for ( $i = 0; $i < count( $users ); $i++ ) {
			if ( filter_var( $users[ $i ][ 1 ], FILTER_VALIDATE_EMAIL ) ) {
				array_push( $this->users, array('name' => $users[$i][0], 'email' => $users[$i][1]) );
				$count_users ++;
			}
		}
		return $count_users;
	}

	/**
	 * The function verifies if every e-mail address is unique.
	 *
	 * @return bool
	 *
	 */
	public function duplicateUsers ()
	{
		for ($i = 0; $i < count($this->users) - 2; $i++) {
		for ($j = $i + 1; $j < count($this->users) - 1; $j++) {
			if ($this->users[$i]['email'] == $this->users[$j]['email']) {
				return false;
			}
		}
	}
		return true;}


	/**
	 * This function return for every participant a random number.
	 * @return int
	 */
	protected function getRandomUserKey() {
		$first = 0;
		$last  = count( $this->users ) - 1;
		return rand( $first, $last );
	}

	/**
	 * Here will be created an array with user.
	 * Value "0" is for an existing user (who was already selected and will have a present), value "1" is for a new user.
	 * Users will be paired as long as the recipient has not already been chosen.
	 */
	protected function pairUsers() {
		$paired_users = array();
		for ( $i = 0; $i < count( $this->users ); $i ++ ) {
			$ok    = 1;
			$tinta = $this->getRandomUserKey();
			for ( $j = 0; $j < $i; $j ++ ) {
				if ( $tinta == $this->users[ $i ] ) {
					$ok = 0;
				}
			}

			if ( $ok == 1 ) {
				$paired_users[ $i ] = $tinta;
			} else {
				$i --;
			}
		}
		return $paired_users;
	}

	public function getSentEmailsAddresses() {
		return $this->sentEmailsAddresses;
	}

	/**
	 * This function validates if all data was written correctly; if sender address is "null" or recommended sum for the
	 * gift is 0, it wouldn't run.
	 * @return bool
	 */
	public function validateData() {
		if ( '' == $this->fromEmail || 0 == $this->recommendedExpenses ) {
			return false;
		} else {
			return true;
		}
	}

	public function goRudolph() {
		if ( $this->validateData() == true ) {
			$paired_users = $this->pairUsers();

			for ( $i = 0; $i < count( $this->users ); $i ++ ) {
				$message = $this->users[ $i ]['name'] . ', the Christmas is coming and for the Secret Santa event you have to buy a present for ' . $this->users[ $paired_users[ $i ] ]['name'] . '.' . "\r\n" .
				           'Also, the recommended value of the gift is ' . $this->recommendedExpenses . '.' . "\r\n" . 'Merry Christmas!';

				mail( $this->users[ $i ]['email'], $this->emailTitle, $message, 'From: ' . $this->fromEmail );
				array_push( $this->sentEmailsAddresses, $this->users[ $i ]['email'] );

			}
		}
	}
}

