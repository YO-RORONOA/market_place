<?php

namespace App\controllers;

use App\core\Controller;
use App\core\middlewares\GuestMiddleware;
use App\core\Request;
use App\core\Application;
use App\models\User;
use App\models\LoginForm;
use App\models\PasswordResetForm;
use App\services\AuthService;
use App\services\EmailService;
use App\services\TokenService;
use App\models\Role;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    private AuthService $authService;
    private EmailService $emailService;
    private UserRepository $userRepository;
    
    public function __construct()
    {
        $this->setLayout('auth');
        $this->authService = new AuthService();
        $this->emailService = new EmailService();
        $this->userRepository = new UserRepository();
        $this->registerMiddleware(new GuestMiddleware([
            'login', 'register', 'forgotPassword', 'resetPassword', 
            'emailVerificationPage', 'passwordResetSent']));
    }
    
    
    public function login(Request $request)
    {
        $loginForm = new LoginForm();
        
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            
            if ($loginForm->validate() && $this->authService->login($loginForm->email, $loginForm->password, $loginForm->rememberMe ?? false)) {
                Application::$app->session->setFlash('success', 'Welcome back!');
                
                // Check if there's a redirect URL set in session
                $redirectUrl = Application::$app->session->get('redirect_after_login') ?? '/';
                Application::$app->session->remove('redirect_after_login');
                
                Application::$app->response->redirect($redirectUrl);
                return;
            }
        }
        
        return $this->render('auth/login', [
            'model' => $loginForm,
            'title' => 'Login'
        ]);
    }
    
    
    public function register(Request $request)
    {
        $user = new User();
        
        if ($request->isPost()) {
            $userData = $request->getBody();
            $user->loadData($userData);
            
            $existingUser = $this->userRepository->findByEmail($user->email);
            
            if ($existingUser) {
                $existingUser->loadRoles();
                
                if ($existingUser->hasRole(Role::BUYER)) {
                    $user->addError('email', 'This email is already registered.');
                } else {
                    if ($this->authService->addRoleToUser($existingUser->id, Role::BUYER)) { 
                        Application::$app->session->setFlash('success', 'Your account has been updated with buyer privileges.');
                        Application::$app->response->redirect('/login');
                        return;
                    } else {
                        Application::$app->session->setFlash('error', 'Failed to update account.');
                    }
                }
            } else {
                $user->primary_role_id = Role::BUYER;
                
                if ($user->validate() && $this->authService->register($userData, Role::BUYER)) {  //passing userdata instead of user object for seperation of concerns(best practices)
                    Application::$app->session->setFlash('success', 'Thanks for registering! Please check your email to verify your account.');
                    Application::$app->session->set('verification_email', $user->email);
                    Application::$app->response->redirect('/email-verification');
                    return;
                }
            }
        }
        
        return $this->render('auth/register', [
            'model' => $user,
            'title' => 'Create Account'
        ]);
    }
    
    
    public function logout()
    {
        $this->authService->logout();
        Application::$app->session->setFlash('success', 'You have been logged out successfully.');
        Application::$app->response->redirect('/login');
    }
    
    
    public function emailVerificationPage()
    {
        // Get email from session if available
        $email = Application::$app->session->get('verification_email') ?? '';
        
        return $this->render('auth/emailverification', [
            'email' => $email,
            'title' => 'Verify Your Email'
        ]);
    }

    public function VerificationSuccess()
    {
        return $this->render('auth/VerificationSuccess');
    }

   
    
    
    public function verifyEmail(Request $request)
    {
        $token = $request->getQuery('token') ?? '';
        
        if (empty($token) || !$this->authService->verifyEmail($token)) {
            Application::$app->session->setFlash('error', 'Invalid or expired verification token.');
            Application::$app->response->redirect('/InvalidToken');
            return;
        }
        
        Application::$app->session->setFlash('success', 'Your email has been verified successfully!');
        return $this->render('auth/verificationSuccess', [
            'title' => 'Email Verified'
        ]);
    }
    
    
    public function resendVerification(Request $request)
    {
        $email = $request->getBody()['email'] ?? Application::$app->session->get('verification_email') ?? '';
        
        if (empty($email)) {
            Application::$app->session->setFlash('error', 'Please provide an email address.');
            Application::$app->response->redirect('/email-verification');
            return;
        }
        
        if ($this->authService->resendVerificationEmail($email)) {
            Application::$app->session->setFlash('success', 'Verification email has been sent again. Please check your inbox.');
        }
        
        Application::$app->response->redirect('/email-verification');
    }
    
    
    public function forgotPassword(Request $request)
    {
        $model = new User();
        
        if ($request->isPost()) {
            $email = $request->getBody()['email'] ?? '';
            $model->email = $email;
            
            if (!empty($email)) {
                $this->authService->requestPasswordReset($email);
                Application::$app->session->setFlash('success', 'If your email exists in our system, you will receive a password reset link shortly.');
                Application::$app->response->redirect('/passwordResetSent');
                return;
            } else {
                $model->addError('email', 'Email is required');
            }
        }
        
        return $this->render('auth/forgot-password', [
            'model' => $model,
            'title' => 'Reset Password'
        ]);
    }
    
    
    public function passwordResetSent()
    {
        return $this->render('auth/passwordResetSent', [
            'title' => 'Reset Link Sent'
        ]);
    }
    
   
    public function resetPassword(Request $request)
    {
        $token = $request->getQuery('token') ?? '';
        
        if (empty($token)) {
            Application::$app->session->setFlash('error', 'Invalid password reset token.');
            Application::$app->response->redirect('/InvalidToken');
            return;
        }
        
        $model = new PasswordResetForm();
        
        if ($request->isPost()) {
            $model->loadData($request->getBody());
            
            if ($model->validate() && $this->authService->resetPassword($token, $model->password)) {
                Application::$app->session->setFlash('success', 'Your password has been reset successfully!');
                return $this->render('auth/PasswordresetSuccess', [
                    'title' => 'Password Reset Complete'
                ]);
            }
        }
        
        return $this->render('auth/ResetPassword', [
            'model' => $model,
            'token' => $token,
            'title' => 'Create New Password'
        ]);
    }
    
   
    public function invalidToken()
    {
        return $this->render('auth/InvalidToken', [
            'title' => 'Invalid Token'
        ]);
    }
}