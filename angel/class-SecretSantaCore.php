<?php
/**
 * Created by PhpStorm.
 * User: angel
 * Date: 8/2/2018
 * Time: 5:33 PM
 *
 * @package angel
 */

/**
 * A class that implements the secret Santa concept
 *
 * Class SecretSantaCoreAngel
 */
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
	 * @param string $email is the email that the user wants to send emails from.
	 *
	 * @return bool
	 */
	public function setEmailFrom( $email ) {
		if ( ! $this->isValidEmailAddress( $email ) ) {
			return false;
		}

		$this->fromEmail = $email;

		return true;
	}

	/**
	 * Sets the title of the emailTitle attribute.
	 *
	 * @throws Exception in case of invalid title.
	 *
	 * @param string $title is the title that the user wants to set for the emails.
	 *
	 * @return true
	 */
	public function setEmailTitle( $title ) {
		$tempTitle = str_split( $title );
		foreach ( $tempTitle as $char ) {
			if ( ( 'A' > $char || 'Z' < $char ) && ( 'a' > $char || 'z' < $char ) && ( '0' > $char || '9' < $char ) && ( ' ' !== $char && '!' !== $char && '.' !== $char ) ) {
				throw new Exception( 'Titlul ' . $title . ' nu este valid!', 0 );
			}
		}
		$this->emailTitle = $title;

		return true;
	}

	/**
	 * Sets the recommendedExpenses attribute
	 *
	 * @param  int | string $allocatedSum is the number that the user wants to set for the price of the gift.
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
	 * Calls the addUser() method for each user
	 *
	 * @param array $newUsers is an array containing the data about the participants that the user wants to add.
	 *
	 * @return int|false The number of added users or false on invalid input format.
	 */
	public function addUsers( $newUsers ) {
		if ( ! is_array( $newUsers ) ) {
			return false;
		}
		$countAddedUsers = 0;
		foreach ( $newUsers as $newUser ) {
			if ( $this->addUser( $newUser ) ) {
				$countAddedUsers ++;
			}
		}

		return $countAddedUsers;
	}

	/**
	 * Checks if the new user is valid for the event.
	 *
	 * If the participant doesn't meat the criteria, code 0 exception is thrown. In case that the user already exists in the event, code 1 is thrown.
	 *
	 * @param array $user is an individual element from the $newUsers array.
	 *
	 * @return bool
	 */
	protected function addUser( $user ) {
		if ( ! is_array( $user ) || count( $user ) < 2 ) {
			return false;
		}

		$user = $this->maybeStandardizeUser( $user );

		if ( false === $user ) {
			return false;
		}

		if ( ! $this->checkParticipant( $user ) ) {
			return false;
		}

		$this->users[] = $user;

		return true;
	}

	/**
	 * Tries to convert the $user array into a valid participant.
	 *
	 * @param array $user is a valid array from witch will be extracted a name and an email if possible.
	 *
	 * @return array|bool false on failure or a valid user upon success
	 */
	public function maybeStandardizeUser( $user ) {
		if ( ! is_array( $user ) || count( $user ) < 2 ) {
			return false;
		}

		$standardUser = array(
			'name'  => false,
			'email' => false,
		);

		if ( isset( $user['email'] ) ) {
			$standardUser['email'] = $user['email'];
		} else {
			foreach ( $user as $key => $detail ) {
				if ( true === $this->isValidEmailAddress( $detail ) ) {
					$standardUser['email'] = $detail;
					unset( $user[ $key ] );
					break;
				}
			}
		}

		if ( false === $standardUser['email'] ) {
			return false;
		}

		if ( isset( $user['name'] ) ) {
			$standardUser['name'] = $user['name'];
		} else {
			foreach ( $user as $key => $detail ) {
				if ( true === $this->checkUserName( $detail ) ) {
					$standardUser['name'] = $detail;
					break;
				}
			}
		}

		if ( false === $standardUser['name'] ) {
			return false;
		}

		return $standardUser;
	}

	/**
	 * Checks if the $email variable contains a valid email address.
	 *
	 * @param string $email the string that will be tested.
	 *
	 * @return bool true if the email is valid, false otherwise
	 */
	protected function isValidEmailAddress( $email ) {
		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Sends the emails
	 *
	 * Generates the random matches between the participants and sends the emails. If the sending succeeds, the receiver's email address is added tot the sentEmailsAddresses attribute.
	 *
	 * @throws Exception in case of insufficient data.
	 * @return void
	 */
	public function goRudolph() {
		if ( ! $this->checkIfReady() ) {
			throw new Exception( 'Fatal error', 0 );
		}

		$colleagues = $this->users;

		$noParticipants = count( $this->users );

		for ( $i = 0; $i < $noParticipants - 1; $i ++ ) {
			$random = wp_rand( $i + 1, $noParticipants - 1 );

			$this->swap( $colleagues[ $i ], $colleagues[ $random ] );
		}

		foreach ( $this->users as $key => $user ) {
			if ( mail(
				$user['email'], $this->emailTitle,
				'Draga ' . $user['name'] . ",\r\nTrebuie sa ii iei cadou lui " .
					$colleagues[ $key ]['name'] . ' cu emailul ' . $colleagues[ $key ]['email'] .
					' in valoare de ' . $this->recommendedExpenses . ' lei!',
				'From: ' . $this->fromEmail
			) ) {
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
			return false;
		}

		if ( empty( $this->recommendedExpenses ) ) {
			return false;
		}

		if ( empty( $this->emailTitle ) ) {
			$this->emailTitle = 'No title';
		}

		if ( count( $this->users ) < 2 ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the pretender meets the criteria.
	 *
	 * The $participant array should have exactly 2 entries, the name should contain only alphabetic characters and the mail must be valid.
	 *
	 * @see filter_var()
	 *
	 * @param array $user is an array with 2 fields; name and email.
	 *
	 * @return bool
	 */
	protected function checkParticipant( $user ) {
		return $this->checkUserEmail( $user['email'] ) && $this->checkUserName( $user['name'] );
	}

	/**
	 * Checks if a user name is valid.
	 *
	 * @param string $name is a string containing the name of a potential user.
	 *
	 * @return bool
	 */
	protected function checkUserName( $name ) {
		if ( ! ctype_alnum( $name ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if a user name is valid.
	 *
	 * @param string $email is a string containing the email of a potential user.
	 *
	 * @return bool
	 */
	protected function checkUserEmail( $email ) {
		return $this->isValidEmailAddress( $email ) && ! $this->participantExists( $email );
	}

	/**
	 * Checks if the pretender isn't already in the event
	 *
	 * If the email corresponds to another email from an user already in the event, the pretender is rejected
	 *
	 * @param string $participantEmail is a string containing the email of a potential user.
	 *
	 * @return bool true if it already exists, false otherwise
	 */
	protected function participantExists( $participantEmail ) {
		if ( empty( $this->users ) ) {
			return false;
		}
		foreach ( $this->users as $user ) {
			if ( $user['email'] === $participantEmail ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Interchanges the values pointed by a and b.
	 *
	 * @param int|string|bool $a  the first value.
	 * @param int|string|bool $b resource the second value.
	 *
	 * @return void
	 */
	protected function swap( &$a, &$b ) {
		$aux = $a;
		$a   = $b;
		$b   = $aux;
	}
}
