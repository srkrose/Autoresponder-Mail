# Autoresponder-Mail
Automated respond mail sending PHP script using PHPMailer for cPanel

### 1. Create Folder and Add Files

Create a folder named `scripts` in cPanel and add the files from the repository to the created folder

### 2. Replace Required Fields

Replace the required fields in the following files:

- /config/.smtpconfig: `Replace with the required SMTP settings of the sender email`

### 3. Add Forwarder 

Navigate to cPanel > Forwarders > Add Forwarder and set up the forwarder as follows:

- Address to Forward: `sender email`
- Destination: Advanced Options > Pipe to a Program > `scripts/pipe/emailpipe.php`

### Reference:

- https://github.com/PHPMailer/PHPMailer
