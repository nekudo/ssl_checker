<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/config.php';
require __DIR__ . '/SslCheck.php';

$sslCheck = new \Nekudo\SslChecker\SslCheck;
$sslCheck->setSites($sites);
$sslCheck->__invoke();
