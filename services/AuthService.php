<?php

namespace App\Services;

use App\core\Application;
use App\models\User;
use App\models\UserStatus;
use App\models\Role;
use App\Repositories\UserRepository;
use App\repositories\UserRoleRepository;
use App\Services\EmailService;
use App\services\TokenService;

class AuthService
{
    private const CART_SESSION_KEY = 'cart_items';
    private UserRepository $userRepository;
    private UserRoleRepository $userRoleRepository;
    private EmailService $emailService;
    private TokenService $tokenService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userRoleRepository = new UserRoleRepository();
        $this->emailService = new EmailService();
        $this->tokenService = new TokenService();
    }

  
    public function register(array $userData, int $roleId = Role::BUYER): bool|int
    {
        $user = new User();
        $user->loadData($userData);
        
        $user->primary_role_id = $roleId;

        $token = $this->tokenService->generateEmailVerificationToken();
        $user->verification_token = $token;

        if($user->validate() && $user->save())
        {
            if ($user->id) {
                $cartService = new \App\services\CartService();
                $cartService->persistVisitorCartToUser($user->id);
            }
            $this->emailService->sendVerificationEmail($user, $token);

            Application::$app->session->setFlash('success', 'Registration successful! Please check your email to verify your account.');
            Application::$app->session->set('verification_email', $user->email);
            
            return $user->id;
        }

        return false;
    }

    
    public function addRoleToUser(int $userId, int $roleId): bool
    {
        return $this->userRoleRepository->addRoleToUser($userId, $roleId);
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
            $user->loadRoles();
            
            if ($user->hasRole(Role::VENDOR)) {
                // Notify admin about verified vendor that needs approval
                // $this->notifyAdminAboutVerifiedVendor($user);
                
                Application::$app->session->setFlash('success', 'Email verified successfully! Your vendor account is now pending admin approval. You can log in to check your status.');
            } else {
                Application::$app->session->setFlash('success', 'Email verified successfully! You can now log in.');
            }
            
            return true;
        }

        return false;
    }

    public function login(string $email, string $password, bool $rememberMe = false, ?int $requiredRole = null): bool
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
        
        $user->loadRoles();
        
        if ($requiredRole !== null && !$user->hasRole($requiredRole)) {
            Application::$app->session->setFlash('error', 'You do not have the required role to access this area.');
            return false;
        }
    
        Application::$app->session->set('user', [
            'id' => $user->id, 
            'name' => $user->getDisplayName(),
            'email' => $user->email,
            'roles' => $user->roles, 
            'active_role' => $requiredRole ?? $user->roles[0] ?? null 
        ]);
        
        $cartService = new \App\services\CartService();
        // $cartService->initializeUserCart($user->id); maybe replace
        $cartService->persistVisitorCartToUser($user->id);
        
        return true;
    }

   
    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function logout(): void
    {
        Application::$app->session->remove(self::CART_SESSION_KEY);
        Application::$app->session->remove('user');
    
    }

    
    public function switchActiveRole(int $roleId): bool
    {
        $userData = Application::$app->session->get('user');
        
        if (!$userData) {
            return false;
        }
        
        $userRoles = $userData['roles'] ?? [];
        
        if (!in_array($roleId, $userRoles)) {
            return false;
        }
        
        $userData['active_role'] = $roleId;
        Application::$app->session->set('user', $userData);
        
        return true;
    }

 
    public function requestPasswordReset(string $email): bool
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
    
    
    public function resendVerificationEmail(string $email): bool
    {
        $user = $this->userRepository->findByEmail($email);
        
        if (!$user || $user->isEmailVerified()) {
            return false;
        }
        
        $token = $this->tokenService->generateEmailVerificationToken();
        
        $this->userRepository->update($user->id, [
            'verification_token' => $token
        ]);
        
        return $this->emailService->sendVerificationEmail($user, $token);
    }
}