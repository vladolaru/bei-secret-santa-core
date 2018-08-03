<?php

use PHPUnit\Framework\TestCase;

final class SecretSantaCoreAngelTest extends TestCase {

	public static function setUpBeforeClass() {
		// Load the class to be tested.
		require_once dirname( dirname( __DIR__ ) ) . '/angel/class-SecretSantaCore.php';
	}

	public function testInvalidMailFrom() {
		$santa = new SecretSantaCoreAngel();

		$invalid_emails = [
			'plainaddress',
			'#@%^%#$@#$@#.com',
			'@example.com',
			'Joe Smith <email@example.com>',
			'email.example.com',
			'email@example@example.com',
			'.email@example.com',
			'email.@example.com',
			'email..email@example.com',
			'あいうえお@example.com',
			'email@example.com (Joe Smith)',
			'email@example',
			'email@-example.com',
		];

		foreach ( $invalid_emails as $invalid_email ) {
			$this->assertEquals( false, $santa->setEmailFrom( $invalid_email ), 'Tried email: ' . $invalid_email );
		}
	}

	public function testValidMailFrom() {
		$santa = new SecretSantaCoreAngel();

		$valid_emails = [
			'email@example.com',
			'firstname.lastname@example.com',
			'email@subdomain.example.com',
			'firstname+lastname@example.com',
			'email@[123.123.123.123]',
			'"email"@example.com',
			'1234567890@example.com',
			'email@example-one.com',
			'_______@example.com',
			'email@example.name',
			'email@example.museum',
			'email@example.co.jp',
			'firstname-lastname@example.com',
		];

		foreach ( $valid_emails as $valid_email ) {
			$this->assertEquals( true, $santa->setEmailFrom( $valid_email ) );
		}
	}

	public function testInvalidRecommendedExpenses() {
		$santa = new SecretSantaCoreAngel();

		$this->assertEquals( false, $santa->setRecommendedExpenses( 'abc' ) );
		$this->assertEquals( false, $santa->setRecommendedExpenses( -100 ) );
	}

	public function testValidRecommendedExpenses() {
		$santa = new SecretSantaCoreAngel();

		$this->assertEquals( true, $santa->setRecommendedExpenses( 1 ) );
		$this->assertEquals( true, $santa->setRecommendedExpenses( 100 ) );
		$this->assertEquals( true, $santa->setRecommendedExpenses( 100.10 ) );
		$this->assertEquals( true, $santa->setRecommendedExpenses( 0.10 ) );
	}

	public function testInvalidAddUsers() {
		$reflection = new ReflectionClass( 'SecretSantaCoreAngel' );
		$users_prop = $reflection->getProperty( 'users' );
		$users_prop->setAccessible(true);

		$santa = new SecretSantaCoreAngel();

		$this->assertEquals( [], $users_prop->getValue( $santa ) );

		$this->assertEquals( false, $santa->addUsers( 0 ) );
		$this->assertEquals( false, $santa->addUsers( 'asdasd' ) );
		$this->assertEquals( 0, $santa->addUsers( ['asdasd',1,[],['asdas']] ) );
		$this->assertEquals( 0, $santa->addUsers( [['Vlad','invalidemail']] ) );

		$this->assertEquals( [], $users_prop->getValue( $santa ) );

		$users_prop->setAccessible(false);
	}

	public function testValidAddUsers() {
		$reflection = new ReflectionClass( 'SecretSantaCoreAngel' );
		$users_prop = $reflection->getProperty( 'users' );
		$users_prop->setAccessible(true);

		$santa = new SecretSantaCoreAngel();

		$this->assertEquals( 1, $santa->addUsers( [['Vlad','test@test.com']] ) );

		$this->assertEquals( [
			[
				'name' => 'Vlad',
				'email' => 'test@test.com',
			],
		], $users_prop->getValue( $santa ) );

		$this->assertEquals( 0, $santa->addUsers( [['Vlad','test@test.com']] ) );
		$this->assertEquals( 0, $santa->addUsers( [[ 'name' => 'Vlad2', 'email' => 'test@test.com']] ) );
		$this->assertEquals( 1, $santa->addUsers( [[ 'name' => 'Vlad2', 'email' => 'test2@test.com']] ) );
		$this->assertEquals( 0, $santa->addUsers( [[ 'email' => 'test2@test.com', 'name' => 'Vlad2']] ) );
		$this->assertEquals( 1, $santa->addUsers( [[ 'email' => 'test22@test.com', 'name' => 'Vlad2']] ) );

		$this->assertEquals( [
			[
				'name' => 'Vlad',
				'email' => 'test@test.com',
			],
			[
				'name' => 'Vlad2',
				'email' => 'test2@test.com',
			],
            [
                'name' => 'Vlad2',
                'email' => 'test22@test.com',
            ],
		], $users_prop->getValue( $santa ) );

		$this->assertEquals( 1, $santa->addUsers( [['Vlad3','test3@test.com'], ['Vlad3','test3@test.com'], ['Vlad3','test3@test.com']] ) );

		$this->assertEquals( [
			[
				'name' => 'Vlad',
				'email' => 'test@test.com',
			],
			[
				'name' => 'Vlad2',
				'email' => 'test2@test.com',
			],
            [
                'name' => 'Vlad2',
                'email' => 'test22@test.com',
            ],
			[
				'name' => 'Vlad3',
				'email' => 'test3@test.com',
			],
		], $users_prop->getValue( $santa ) );

		$this->assertEquals( 2, $santa->addUsers( [['Vlad3','test3@test.com'], ['Vlad4','test4@test.com'], ['Vlad4','test4@test.com'], ['Vlad5','test5@test.com']] ) );
	}
}
