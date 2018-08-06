<?php

/**
 * Used for the purpose of the Secret Santa type events.
 */
class SecretSantaCoreCosmin {

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
	 * @param null | string $email
	 *
	 * @return bool False if the mail fails the validation filter.
	 *              Otherwise, it returns true.
	 */
	public function setFromEmail( $email ) {
		if ( null == filter_var( $email, FILTER_VALIDATE_EMAIL ) || false == filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		} else {
			$this->fromEmail = $email;

			return true;
		}
	}

	/**
	 * Sets the recommendedExpenses attribute.
	 *
	 * @param int | float $sum
	 *
	 * @return bool True if the value is a numeric one or if it's bigger than 0.
	 *              Otherwise, it returns false.
	 */
	public function setRecommendedExpenses( $sum ) {
		if ( is_numeric( $sum ) && $sum > 0 ) {
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
	 * @param null | string $title
	 */
	public function setEmailTitle( $title ) {
		$title = trim( $title );
		if ( '' == $title ) {
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
	public function getSentEmailsAddresses() {
		return $this->sentEmailsAddresses;
	}

	/**
	 * Adds all the given users in an array.
	 *
	 *
	 * @param $users array
	 *
	 * @return bool False if the array given is empty or if the value given is not an array.
	 *              If there are any given parameters that are not valid, the function just skips them.
	 *              Otherwise, it returns true.
	 */
	public function addUsers( $users ) {
		if ( empty( $users ) || ! is_array( $users ) ) {
			return false;
		}
		$userCount = 0;

		foreach ( $users as $user ) {

			if ( $this->makeUserStandard( $user ) == false ) {
				return false;
			}

			$standardUser = $this->makeUserStandard( $user );


			if ( 2 != count( $standardUser ) ||
			     false == filter_var( $standardUser[1], FILTER_VALIDATE_EMAIL ) ||
			     '' == trim( $standardUser[0] ) ||
			     ! ctype_alnum( $standardUser[0] ) ||
			     false == $this->checkDuplicateEmails( $standardUser ) ) {
				continue;
			}
			array_push( $this->users, array(
					'name'  => $standardUser[0],
					'email' => $standardUser[1],
				)
				);


			$userCount ++;
		}

		return $userCount;
	}

	/**
	 * Standardizes and modifies the values given, so that the addUsers function will not meet any input errors.
	 *
	 * @param array
	 *
	 * @return array | bool
	 */
	protected function makeUserStandard( $user ) {

		$standardUser = array();

		if ( ! is_array( $user ) ) {
			return false;
		}

		if ( array_key_exists( 'name', $user ) && array_key_exists( 'email', $user ) ) {

			$standardUser[0] = $user['name'];
			$standardUser[1] = $user['email'];
			unset( $user['name'] );
			unset( $user['email'] );

			return $standardUser;
		}
		if ( array_key_exists( 0, $user ) && array_key_exists( 1, $user ) ) {
			$standardUser[0] = $user[0];
			$standardUser[1] = $user[1];

			return $standardUser;
		} else {
			return false;
		}
	}

	/**
	 * At the entry moment of an user, the function checks if there is already an user with
	 * the same email in the class user array.
	 *
	 * @param array
	 *
	 * @return bool
	 */
	protected function checkDuplicateEmails( $standardUser ) {

		if ( 0 == count( $this->users ) ) {
			return true;
		}
		for ( $i = 0; $i <= count( $this->users ) - 1; $i ++ ) {
			if ( $this->users[ $i ]['email'] == $standardUser[1] ) {
				return false;
			}
		}

		return true;

	}

	/**
	 * Checks if there is an email address wrongly written, or if there are 2 emails that are the same.
	 *
	 *
	 * @return bool False if there is an invalid email or if there are users with the same email.
	 *              Otherwise returns true.
	 */
	protected function validateUsersEmails() {
		for ( $i = 0; $i < count( $this->users ); $i ++ ) {
			if ( false == filter_var( $this->users[ $i ]['email'], FILTER_VALIDATE_EMAIL ) ) {
				return false;
			}
		}

		for ( $i = 0; $i < count( $this->users ) - 2; $i ++ ) {
			for ( $j = $i + 1; $j < count( $this->users ) - 1; $j ++ ) {
				if ( $this->users[ $i ]['email'] == $this->users[ $j ]['email'] ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Selects, from a given range, a random value, which is needed to do the pairing of the users.
	 *
	 * @return int
	 */
	protected function randomizeUsersKey() {
		$firstUser = 0;
		$lastUser  = count( $this->users ) - 1;

		return rand( $firstUser, $lastUser );
	}

	/**
	 * Checks if all the functions are properly used, so that there will be no unexpected results after we Run the code.
	 *
	 * @return bool False if there are less than 3 users or no email address from which we will send emails,
	 *              or if the required sum for the gifts is equal to 0, or if the emails validation function fails.
	 *              Otherwise, it returns true.
	 */
	public function attributesCheck() {
		if ( count( $this->users ) < 3 ||
		     '' == $this->fromEmail ||
		     0 == $this->recommendedExpenses ||
		     false == $this->validateUsersEmails() ) {

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
	protected function doPairing() {
		$frequency = array();
		for ( $i = 0; $i < count( $this->users ); $i ++ ) {
			$frequency[ $i ] = 0;
		}

		for ( $i = 0; $i < count( $this->users ); $i ++ ) {
			$randomUser = $this->randomizeUsersKey();

			while ( 0 != $frequency[ $randomUser ] || $randomUser == $i ) {
				$randomUser = $this->randomizeUsersKey();
			}
			$this->pairing[ $i ]      = $randomUser;
			$frequency[ $randomUser ] = 1;
		}
	}

	/**
	 * Checks if every required function was used properly, does the pairing between the given users.
	 *
	 * Sends the emails to the users, and, one by one, if the email is accepted for delivery, it is added to the sentEmailAddresses attribute.
	 *
	 * @see doPairing() - to understand how the pairings are done and how/to who are the emails sent.
	 */
	public function goRudolph() {

		if ( $this->attributesCheck() == true ) {

			$this->doPairing();

			for ( $i = 0; $i < count( $this->users ); $i ++ ) {

				$msg = 'Dear ' . $this->users[ $i ]['name'] . ',' . "\r\n" . "\r\n" . 'The special person that you have to buy a present for, for the occasion of the Secret Santa Event, is ' . $this->users[ $this->pairing[ $i ] ]['name'] . ' with the email '
				       . $this->users[ $this->pairing[ $i ] ]['email'] . ' and the recommended value of the present is ' . $this->recommendedExpenses
				       . ' dollars. Have a jolly day!';

				if ( mail( $this->users[ $i ]['email'], $this->emailTitle, $msg, 'From: ' . $this->fromEmail ) ) {
					array_push( $this->sentEmailsAddresses, $this->users[ $i ]['email'] );
				}
			}

		} else {
			echo "Not all the information written is correct.  ";
		}
	}
}


