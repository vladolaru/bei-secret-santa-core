<?php
/**
 * PHPUnit bootstrap file for Angel.
 */

// Load the PHPUnit framework.
require dirname( __DIR__ ) . '/vendor/autoload.php';

// Load the code to be tested.
require_once dirname( dirname( __DIR__ ) ) . '/angel/class-SecretSantaCore.php';
