<?php
require_once 'class-SecretSantaCore.php';

// Determine the intern name based on the current directory.
$intern_name = ucfirst( basename( __DIR__ ) );

// Some sanity check.
if ( ! class_exists('SecretSantaCoreCosmin' ) ) {

	include dirname( __DIR__ ) . '/error.php';
	die;
}

// Create a new instance.
$santa = new SecretSantaCoreCosmin();

// More sanity check.
if ( ! method_exists( $santa, 'setFromEmail' ) ||
     ! method_exists( $santa, 'setEmailTitle' ) ||
     ! method_exists( $santa, 'setRecommendedExpenses' ) ||
     ! method_exists( $santa, 'addUsers' ) ||
     ! method_exists( $santa, 'goRudolph' ) ||
     ! method_exists( $santa, 'getSentEmailsAddresses' ) ) {

	include dirname( __DIR__ ) . '/error.php';
	die;
}

// Set the email address the emails will be sent from.
$santa->setFromEmail('santa@northpole.com');

// Set the sent emails' title.
$santa->setEmailTitle( 'You have some gifting to do..' );

// Set the recommended expenses value.
$santa->setRecommendedExpenses( 10 );

// Set the users that are participating in the Secret Santa game.
$santa->addUsers( [['Vlad','test@test.com']] );
$santa->addUsers( [['Vlad','test@test.com']] );
$santa->addUsers( [[ 'name' => 'Vlad2', 'email' => 'test@test.com']] );
$santa->addUsers( [[ 'name' => 'Vlad2', 'email' => 'test2@test.com']] );
$santa->addUsers( [[  'email' => 'test2@test.com', 'name' => 'Vlad2']] );
$santa->addUsers( [[  'email' => 'test22@test.com', 'name' => 'Vlad2']] );
$santa->addUsers( [['Vlad3','test3@test.com'], ['Vlad3','test3@test.com'], ['Vlad3','test3@test.com']] );


// Pair users and send them the emails with the necessary emails.
$santa->goRudolph();

// Get some feedback data for double checking
echo 'For logging purposes, here are the email addresses we\'ve sent to: ' . PHP_EOL;
print_r( $santa->getSentEmailsAddresses() );
