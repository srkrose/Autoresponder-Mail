<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$directoryPath = dirname(dirname(__DIR__));

require $directoryPath . '/scripts/PHPMailer/src/Exception.php';
require $directoryPath . '/scripts/PHPMailer/src/PHPMailer.php';
require $directoryPath . '/scripts/PHPMailer/src/SMTP.php';

include $directoryPath . '/scripts/config/smtpconfig.php';

// Set up PHPMailer
$mail = new PHPMailer(true); // Enable exceptions for error handling
$mail->isSMTP();
$mail->SMTPDebug = $smtpdebug;

$mail->Host = $smtphost;
$mail->Port = $smtpport;
$mail->SMTPSecure = $smtpsecure;
$mail->SMTPAuth = true;
$mail->Username = $smtpUsername;
$mail->Password = $smtpPassword;

$mail->setFrom($emailFrom, $emailFromName);

// Read the recipient email address and subject from the file
$recipientListFile = $directoryPath . '/scripts/pipe/emailpipe.txt';
$fileContents = file($recipientListFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (count($fileContents) >= 2) {
    $recipient = trim($fileContents[0]);     // Read the first line (recipient)
    $subject = trim($fileContents[1]);        // Read the second line (subject)
} else {
    echo "Not enough lines in the file to get recipient and subject.\n";
    exit;
}

$mail->Subject = "RE: $subject";

$mail->isHTML(true);

// Read the email body content from the file
$emailBodyFile = $directoryPath . '/scripts/pipe/emailbody.html';
$emailBody = file_get_contents($emailBodyFile);

// Include the HTML content in the email body
$mail->Body = $emailBody;

// Check if a recipient was found in the file
if (!empty($recipient) && strtolower($recipient) !== strtolower($emailFrom)) {
    $mail->clearAddresses();
    $mail->addAddress($recipient);

    // Send the email
    if ($mail->send()) {
        // Email sent successfully, remove the file
        unlink($recipientListFile);
    } else {
        $errorMessage = "Failed to send the email to: $recipient. Error: " . $mail->ErrorInfo;
        error_log($errorMessage);
        unlink($recipientListFile);
    }
} else {
    echo "No recipient found in the file.\n";
    unlink($recipientListFile);
}

?>
