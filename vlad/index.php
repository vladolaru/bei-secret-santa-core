<?php
require_once 'class-SecretSantaCore.php';

// Determine the intern name based on the current directory.
$intern_name = ucfirst( basename( __DIR__ ) );

// Some sanity check.
if ( ! class_exists('SecretSantaCore' ) ) {

	include dirname( __DIR__ ) . '/error.php';
	die;
}

// Create a new instance.
$santa = new SecretSantaCore();

// More sanity check.
if ( ! method_exists( $santa, 'setMailFrom' ) ||
     ! method_exists( $santa, 'setMailTitle' ) ||
     ! method_exists( $santa, 'setRecommendedValue' ) ||
     ! method_exists( $santa, 'setUsers' ) ||
     ! method_exists( $santa, 'run' ) ||
     ! method_exists( $santa, 'getSentEmailsAddresses' ) ) {

	include dirname( __DIR__ ) . '/error.php';
	die;
}

// Set the email address the emails will be sent from.
$santa->setMailFrom('santa@northpole.com');

// Set the sent emails' title.
$santa->setMailTitle( 'You have some gifting to do..' );

// Set the recommended expenses value.
$santa->setRecommendedValue( 10 );

// Set the users that are participating in the Secret Santa game.
$santa->setUsers(
		[
				[ 'Vlad', 'vlad@secretsanta.com', ],
				[ 'Angel', 'angel@secretsanta.com', ],
				[ 'Cosmin', 'cosmin@secretsanta.com', ],
				[ 'Ionel', 'ionel@secretsanta.com', ],
				[ 'Viorica', 'viorica', ],
				[ 'Simona', '12344.com', ],
		]
);

// Pair users and send them the emails with the necessary emails.
$santa->run();

// Get some feedback data for double checking
echo 'For logging purposes, here are the email addresses we\'ve sent to:' . PHP_EOL;
print_r( $santa->getSentEmailsAddresses() );
