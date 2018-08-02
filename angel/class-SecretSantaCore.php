<?php

class SecretSantaCoreAngel {
	/**
	 * The email that will sent the messages
	 *
	 * @var null | string
	 */
	protected $fromEmail = null;
	/**
	 * The title of the email
	 *
	 * @var null | string
	 */
	protected $emailTitle = null;
	/**
	 * The recommended value of the gift
	 *
	 * @var null | int | float
	 */
	protected $recommendedExpenses = null;
	/**
	 * The list of people that will participate in the event
	 *
	 * @var array
	 */
	protected $users = array();
	/**
	 * The list of emails that were sent
	 *
	 * @var array
	 */
	protected $sentEmailsAddresses = array();

	/**
	 * SecretSantaCore constructor.
	 */
	public function __construct() {

	}

	/**
	 * Sets the fromEmail attribute
	 *
	 * @param string $email
	 *
	 * @return bool
	 */
	public function setEmailFrom( $email ) {
		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		$this->fromEmail = $email;

		return true;
	}

	/**
	 * Sets the title of the emailTitle attribute
	 *
	 * @throws Exception in case of invalid title
	 *
	 * @param $title string
	 *
	 * @return true
	 */
	public function setEmailTitle( $title ) {
		$tempTitle = str_split( $title );
		foreach ( $tempTitle as $char ) {
			if ( ! ( ( $char >= 'A' && $char <= 'Z' ) || ( $char >= 'a' && $char <= 'z' ) ||
			         ( $char >= '0' && $char <= '9' ) || ( $char == ' ' || $char == '!' || $char == '.' ) ) ) {
				throw new Exception( 'Titlul ' . $title . ' nu este valid!', 0 );
			}
		}
		$this->emailTitle = $title;

		return true;
	}

	/**
	 * Sets the recommendedExpenses attribute
	 *
	 * @param $allocatedSum int | string
	 *
	 * @return bool
	 */
	public function setRecommendedExpenses( $allocatedSum ) {
		if ( ! is_numeric( $allocatedSum ) ) {
			return false;
		}

		if ( $allocatedSum <= 0 ) {
			return false;
		}

		$this->recommendedExpenses = $allocatedSum;

		return true;
	}

	/**
	 * Calls the addUser() method for each user and throws the appropriate error for each one
	 *
	 * @param $newUsers array
	 *
	 * @return int|false The number of added users or false on invalid input format.
	 */
	public function addUsers( $newUsers ) {
		$countAddedUsers = 0;
		foreach ( $newUsers as $newUser ) {
			if ( $this->addUser( $newUser ) ) {
				$countAddedUsers ++;
			}
		}

		return $countAddedUsers;
	}

	/**
	 * Sends the emails
	 *
	 * Generates the random matches between the participants and sends the emails. If the sending succeeds, the receiver's email address is added tot the sentEmailsAddresses attribute.
	 *
	 * @throws Exception in case of insufficient data
	 * @return void
	 */
	public function goRudolph() {
		if ( ! $this->checkIfReady() ) {
			throw new Exception( 'Fatal error', 0 );
		}

		$colleagues = $this->users;

		$noParticipants = count( $this->users );

		for ( $i = 0; $i < $noParticipants - 1; $i ++ ) {
			$random = rand( $i + 1, $noParticipants - 1 );

			$this->swap( $colleagues[ $i ], $colleagues[ $random ] );
		}

		foreach ( $this->users as $key => $user ) {
			if (
			mail( $user['email'], $this->emailTitle,
				"Draga " . $user['name'] . ",\r\nTrebuie sa ii iei cadou lui " .
				$colleagues[ $key ]['name'] . " cu emailul " . $colleagues[ $key ]['email'] .
				" in valoare de " . $this->recommendedExpenses . " lei!",
				"From: " . $this->fromEmail
			)
			) {
				array_push( $this->sentEmailsAddresses, $user['email'] );
			}
		}

	}

	/**
	 * Returns the array that contains the email addresses to which notifications were sent
	 *
	 * Must be called after the goRudolph() for conclusive results
	 *
	 * @return array
	 */
	public function getSentEmailsAddresses() {
		return $this->sentEmailsAddresses;
	}


	/**
	 * Checks if all the info necessary for sending the emails is present.
	 *
	 * There must be at least 2 participants, fromEmail and recommendedExpenses must be set and in case there was not title given, emailTitle is set to 'No title'
	 *
	 * @return bool true if ready, false otherwise
	 */
	protected function checkIfReady() {
		if ( empty( $this->fromEmail ) ) {
			echo "error: emailTitle nu poate lipsi!" . "<br>";

			return false;
		}

		if ( empty( $this->recommendedExpenses ) ) {
			echo "error: recommendedExpenses nu poate lipsi!" . "<br>";

			return false;
		}

		if ( empty( $this->emailTitle ) ) {
			$this->emailTitle = 'No title';
			echo "warning: emailTitle nu a fost setat si se va folosi titlul \'No title\'" . "<br>";
		}

		if ( count( $this->users ) < 2 ) {
			echo "error: numar invalid de participanti!" . "<br>";

			return false;
		}

		return true;
	}

	/**
	 * Checks if the new user is valid for the event
	 *
	 * If the participant doesn't meat the criteria, code 0 exception is thrown. In case that the user already exists in the event, code 1 is thrown
	 *
	 * @param $user array
	 *
	 * @return bool
	 */
	protected function addUser( $user ) {
		if ( ! $this->checkParticipant( $user ) ) {
			return false;
		}

		if ( $this->participantExists( $user[1] ) ) {
			return false;
		}

		$this->users[] = array(
			'name'  => $user[0],
			'email' => $user[1],
		);

		return true;
	}

	/**
	 * Checks if the pretender meets the criteria
	 *
	 * The $participant array should have exactly 2 entries, the name should contain only alphabetic characters and the mail must be valid
	 *
	 * @see filter_var()
	 *
	 * @param $participant array
	 *
	 * @return bool
	 */
	protected function checkParticipant( $participant ) {
		if ( count( $participant ) != 2 ) {
			return false;
		}

		if ( ! ctype_alpha( $participant[0] ) ) {
			return false;
		}

		if ( ! filter_var( $participant[1], FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the pretender isn't already in the event
	 *
	 * If the email corresponds to another email from an user already in the event, the pretender is rejected
	 *
	 * @param $participantEmail string
	 *
	 * @return bool true if it already exists, false otherwise
	 */
	protected function participantExists( $participantEmail ) {
		foreach ( $this->users as $user ) {
			if ( $user['email'] == $participantEmail ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Interchanges the values pointed by a and b
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return void
	 */
	protected function swap( &$a, &$b ) {
		$aux = $a;
		$a   = $b;
		$b   = $aux;
	}
}
