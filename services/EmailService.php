<?php


namespace App\Services;

use App\core\Application;
use App\models\User;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;


/**
 * EmailService class handles sending and managing emails.
 * Provides methods for composing, sending, and logging emails.
 * Utilizes external email providers for delivery. 
 * @package Market\Services
 */
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


    }


    public function sendVerificationEmail(User $user, string $token): bool
        {
            try{
                    $verificationUrl = Application::$app->request->getHostInfo() . '/verify-email?token=' . $token;

                    $this->mailer->addAddress(address: $user->email, name: $user->getDisplayName());
                    $this->mailer->Subject = 'Verify you email address';
                    $this->mailer->Body = "
                    <h1>Welcome to YOUMarket</h1>
                    <p>Please verify your email address by clicking the link below:</p>
                    <p><a href=\"{$verificationUrl}\">Verify Email</a></p>
                    <p>If you did not create an account, you can ignore this email.</p>
                    ";
                    return $this->mailer->send();
            }
            catch (Exception $e) {
                Application::$app->session->setFlash('error', 'Could not send verification email: ' . $this->mailer->ErrorInfo);
                return false;
            }
        }


        public function sendPasswordResetEmail(User $user, string $token): bool
        {
            try
            {
                $resetUrl = Application::$app->request->getHostInfo(). '/reset-password?token=' . $token;

                $this->mailer->addAddress($user->email, $user->getDisplayName());
                $this->mailer->Subject = 'Reset your password';
                $this->mailer->Body = "
                    <h1>Password Reset Request</h1>
                    <p>You requested a password reset. Click the link below to reset your password:</p>
                    <p><a href=\"{$resetUrl}\">Reset Password</a></p>
                    <p>If you did not request a password reset, you can ignore this email.</p>
                ";
                
                return $this->mailer->send();
            } catch (Exception $e) {
                Application::$app->session->setFlash('error', 'Could not send password reset email: ' . $this->mailer->ErrorInfo);
                return false;
            }
        }
            


}