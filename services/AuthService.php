<?php

namespace App\Services;

use App\core\Application;
use App\models\User;
use App\models\UserStatus;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\services\TokenService;

class AuthService
{
    private UserRepository $userRepository;
    private EmailService $emailService;
    private TokenService $tokenService;


    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->emailService = new EmailService();
        $this->tokenService = new TokenService();
    }

    public function register(array $userData): bool
    {
        $user = new User();
        $user->loadData($userData);

        $token = $this->tokenService->generateEmailVerificationToken();
        $user->verification_token = $token;

        if($user->validate() && $user->save())
        {
            $this->emailService->sendVerificationEmail($user, $token);

            Application::$app->session->setFlash('sucess', 'Registration successful');
            return true;
        }

        return false;

    }

    public function verifyEmail(string $token): bool
    {
        $user = $this->userRepository->findByVerificationToken($token);

        if(!$user)
        {
            Application::$app->session->setFlash('error', 'Invalid verification token');
            return false;
        }

        $user->verification_token = null;
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->status = UserStatus::ACTIVE;

        if ($user->update()) {
            Application::$app->session->setFlash('success', 'Email verified successfully! You can now log in.');
            return true;
        }

        return false;
    }


    public function login(string $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if(!$user || !password_verify($password, $user->password))
        {
            Application::$app->session->setFlash('error', 'Invalid email or password.');
            return false;
        }

        if(!$user->isEmailVerified())
        {
            Application::$app->session->setFlash('error', 'Please verify your email before logging in.');
            return false;
        }

        if (!$user->isActive()) {
            Application::$app->session->setFlash('error', 'Your account is not active.');
            return false;
        }

        Application::$app->session->set('user',[
            'id' => $user->id, 
            'name' => $user->getDisplayName(),
            'email' => $user->email,
            'role_id' => $user->role_id
        ]);
        return true;
    }


    public function logout(): void
    {
        Application::$app->session->remove('user');
    }

    public function requestPasswordreset(string $email): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if(!$user)
        {
            Application::$app->session->setFlash('error', 'If your email exists in our system, you will receive a password reset link.');
            return false;
        }
        $token = $this->tokenService->generatePasswordRestToken();

        $this->userRepository->savePasswordResetToken($user->id, $token);

        $this->emailService->sendPasswordResetEmail($user, $token);
        
        Application::$app->session->setFlash('success', 'Password reset link sent to your email.');
        return true;

        
    }


    public function resetPassword(string $token, string $password): bool
    {
        $userId = $this->userRepository->findUserIdByPasswordResetToken($token);
    
        if (!$userId) {
            Application::$app->session->setFlash('error', 'Invalid or expired password reset token.');
            return false;
        }
        
        $userData = $this->userRepository->findOne($userId);
        
        if (!$userData) {
            return false;
        }
        
        $user = new User();
        $user->loadData($userData);
        
        $user->password = $password;
        
       
        
        if ($this->userRepository->update($userId, [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ])) {
            $this->userRepository->removePasswordResetToken($token);
            
            Application::$app->session->setFlash('success', 'Password reset successful! You can now log in with your new password.');
            return true;
        }
        
        return false;
    }
}
    
    




