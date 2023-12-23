#!/usr/local/bin/php -q

<?php

$fd=fopen("php://stdin","r");
$email="";
while(!feof($fd)){
$email.=fread($fd,1024);
}
fclose($fd);

$lines = explode("\n", $email);
$from = "";
$headers = "";
$is_header= true;

for ($i = 0; $i < count($lines); $i++) {
    if ($is_header) {
        $headers .= $lines[$i] . "\n";
        
        // Check if the line contains a "Subject:" header
        if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
            $subject = $matches[1];

            // Check if the subject contains "Mail delivery failed"
            if (strpos($subject, 'Mail delivery failed') !== false) {
                // If it contains the specified text, exit the script
                exit;
            }
        }

        // Check if the line contains a "From:" header
        if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
            $from = $matches[1];
            
            // First, check if it's in the format "help desk <email>"
            if (preg_match('/<([^>]+)>/', $from, $emailMatches)) {
                $fromEmail = $emailMatches[1];
            } else {
                // If it's not in the above format, assume it's just an email
                $fromEmail = $from;
            }
        }
    }
}

// Check if either $subject or $fromEmail is empty
if (isset($subject) && isset($fromEmail) && !empty($subject) && !empty($fromEmail)) {
    // Create and write to the file
    $directoryPath = dirname(dirname(__DIR__));
    $fdw = fopen("$directoryPath/scripts/pipe/emailpipe.txt", "w+");
    fwrite($fdw, $fromEmail . "\n");
    fwrite($fdw, $subject . "\n");
    fclose($fdw);

    // Include the autorespond.php script
    require $directoryPath . '/scripts/pipe/autorespond.php';
}

?>
