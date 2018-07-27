<?php
require_once "class-SecretSantaCore.php";

$santa = new SecretSantaCore();

$santa->setFromEmail('test@test.com');

$santa->setEmailTitle('Santa is comeing!');

$santa->setRecomendedExpenses(5);

$santa->addUsers(
    [
        ['Angel', 'angelush@hotmail.com'],
        ['Cosmin', 'erawsi@ma.bate'],
        ['Vladut', 'iamvlad@boss.ro']
    ]
);
var_dump($santa);