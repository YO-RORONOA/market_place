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

        if ($user->save()) {
            Application::$app->session->setFlash('success', 'Email verified successfully! You can now log in.');
            return true;
        }

        return false;
    }



}