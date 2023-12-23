<?php

$directoryPath = dirname(dirname(__DIR__));

$configFilePath = $directoryPath . '/scripts/config/.smtpconfig';

// Read and parse the hidden config file
$config = parse_ini_file($configFilePath);

// Extract values from the config array
$smtpdebug = $config['smtp_debug'];
$smtphost = $config['smtp_host'];
$smtpport = $config['smtp_port'];
$smtpsecure = $config['smtp_secure'];
$smtpUsername = $config['smtp_username'];
$smtpPassword = $config['smtp_password'];
$emailFrom = $config['email_from'];
$emailFromName = $config['email_from_name'];

?>
