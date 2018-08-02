<?php
require_once 'class-SecretSantaCore.php';

// Determine the intern name based on the current directory.
$intern_name = ucfirst( basename( __DIR__ ) );

// Some sanity check.
if ( ! class_exists('SecretSantaCoreAngel' ) ) {

	include dirname( __DIR__ ) . '/error.php';
	die;
}

// Create a new instance.
$santa = new SecretSantaCoreAngel();

// More sanity check.
if ( ! method_exists( $santa, 'setMailFrom' ) ||
     ! method_exists( $santa, 'setEmailTitle' ) ||
     ! method_exists( $santa, 'setRecommendedExpenses' ) ||
     ! method_exists( $santa, 'addUsers' ) ||
     ! method_exists( $santa, 'goRudolph' ) ||
     ! method_exists( $santa, 'getSentEmailsAddresses' ) ) {

	include dirname( __DIR__ ) . '/error.php';
	die;
}

// Set the email address the emails will be sent from.
try {
    $santa->setMailFrom('santa@northpole.com');
} catch (Exception $e) {
    print_r( $e->getMessage() );
}

// Set the sent emails' title.
try {
    $santa->setEmailTitle('You have some gifting to do..');
} catch (Exception $e) {
    print_r( $e->getMessage() );
}

// Set the recommended expenses value.
try {
    $santa->setRecommendedExpenses(3.141592);
} catch (Exception $e) {
    print_r( $e->getMessage() );
}

// Set the users that are participating in the Secret Santa game.
try {
    $santa->addUsers(
        [
            ['Vlad', 'vlad@secretsanta.com',],
            ['Angel', 'angel@secretsanta.com',],
            ['Cosmin', 'cosmin@secretsanta.com',],
            ['Ionel', 'ionel@secretsanta.com',],
            ['Viorica', 'viorica',],
            ['Simona', '12344.com',],
            ['Ionel', 'ionel@secretsanta.com', 'ceapapa'],
            ['Ionel23', 'ionel@secretsanta.com',],
            ['Cosmin', 'cosmin@secretsanta.com',],
        ]
    );
} catch (Exception $e) {
    print_r( $e->getMessage() );
}

// Pair users and send them the emails with the necessary emails.
try {
    $santa->goRudolph();
} catch (Exception $e) {
    print_r( $e->getMessage() );
}

// Get some feedback data for double checking
echo 'For logging purposes, here are the email addresses we\'ve sent to:' . PHP_EOL;
print_r( $santa->getSentEmailsAddresses() );
