<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Request;
use App\core\Application;
use App\models\User;
use App\models\LoginForm;
use App\models\PasswordResetForm;
use App\services\AuthService;
use App\services\EmailService;
use App\services\TokenService;

class AuthController extends Controller
{
    private AuthService $authService;
    private EmailService $emailService;
    
    public function __construct()
    {
        $this->setLayout('auth');
        $this->authService = new AuthService();
        $this->emailService = new EmailService();
    }
    
    /**
     * Display the login page and handle login requests
     */
    public function login(Request $request)
    {
        $loginForm = new LoginForm();
        
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            
            if ($loginForm->validate() && $this->authService->login($loginForm->email, $loginForm->password, $loginForm->rememberMe ?? false)) {
                Application::$app->session->setFlash('success', 'Welcome back!');
                Application::$app->response->redirect('/');
                return;
            }
        }
        
        return $this->render('auth/login', [
            'model' => $loginForm,
            'title' => 'Login'
        ]);
    }
    
    /**
     * Display the registration page and handle registration requests
     */
    public function register(Request $request)
    {
        $user = new User();
        
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            
            if ($user->validate() && $this->authService->register($request->getBody())) {
                Application::$app->session->setFlash('success', 'Thanks for registering! Please check your email to verify your account.');
                Application::$app->response->redirect('/email-verification');
                return;
            }
        }
        
        return $this->render('auth/register', [
            'model' => $user,
            'title' => 'Create Account'
        ]);
    }
    
    /**
     * Handle user logout
     */
    public function logout()
    {
        $this->authService->logout();
        Application::$app->session->setFlash('success', 'You have been logged out successfully.');
        Application::$app->response->redirect('/login');
    }
    
    /**
     * Display the email verification page (after registration)
     */
    public function emailVerificationPage()
    {
        // Get email from session if available
        $email = Application::$app->session->get('verification_email') ?? '';
        
        return $this->render('auth/email-verification', [
            'email' => $email,
            'title' => 'Verify Your Email'
        ]);
    }
    
    /**
     * Handle email verification token
     */
    public function verifyEmail(Request $request)
    {
        $token = $request->getQuery('token') ?? '';
        
        if (empty($token) || !$this->authService->verifyEmail($token)) {
            Application::$app->session->setFlash('error', 'Invalid or expired verification token.');
            Application::$app->response->redirect('/invalid-token');
            return;
        }
        
        Application::$app->session->setFlash('success', 'Your email has been verified successfully!');
        return $this->render('auth/verification-success', [
            'title' => 'Email Verified'
        ]);
    }
    
    /**
     * Resend verification email
     */
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
    
    /**
     * Display the forgot password page and handle password reset requests
     */
    public function forgotPassword(Request $request)
    {
        $model = new User();
        
        if ($request->isPost()) {
            $email = $request->getBody()['email'] ?? '';
            $model->email = $email;
            
            if (!empty($email)) {
                $this->authService->requestPasswordReset($email);
                Application::$app->session->setFlash('success', 'If your email exists in our system, you will receive a password reset link shortly.');
                Application::$app->response->redirect('/password-reset-sent');
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
    
    /**
     * Display password reset sent confirmation page
     */
    public function passwordResetSent()
    {
        return $this->render('auth/password-reset-sent', [
            'title' => 'Reset Link Sent'
        ]);
    }
    
    /**
     * Display and handle password reset form
     */
    public function resetPassword(Request $request)
    {
        $token = $request->getQuery('token') ?? '';
        
        if (empty($token)) {
            Application::$app->session->setFlash('error', 'Invalid password reset token.');
            Application::$app->response->redirect('/invalid-token');
            return;
        }
        
        $model = new PasswordResetForm();
        
        if ($request->isPost()) {
            $model->loadData($request->getBody());
            
            if ($model->validate() && $this->authService->resetPassword($token, $model->password)) {
                Application::$app->session->setFlash('success', 'Your password has been reset successfully!');
                return $this->render('auth/password-reset-success', [
                    'title' => 'Password Reset Complete'
                ]);
            }
        }
        
        return $this->render('auth/reset-password', [
            'model' => $model,
            'token' => $token,
            'title' => 'Create New Password'
        ]);
    }
    
    /**
     * Display error page for invalid token
     */
    public function invalidToken()
    {
        return $this->render('auth/invalid-token', [
            'title' => 'Invalid Token'
        ]);
    }
}