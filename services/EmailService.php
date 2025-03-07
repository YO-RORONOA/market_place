<?php


namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    private PHPMailer $mailer;

    public function __construct()
    {

        $this->mailer = new PHPMailer(exceptions: true);
        $this->mailer->isSMTP(); //protocol to send mail more secure
        $this->mailer->Host = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['MAIL_USERNAME'] ?? '';
        $this->mailer->Password = $_ENV['MAIL_PASSWORD'] ?? '';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //type of connection encryption
        $this->mailer->Port = $_ENV['MAIL_PORT'] ?? 587;
        $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME'] ?? 'YOUMarket');
        $this->mailer->isHTML(true);


        public 




    }


}