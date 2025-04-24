<?php

namespace App\controllers;

use App\core\Application;
use App\core\Controller;
use App\core\middlewares\GuestMiddleware;
use App\core\Request;
use App\models\User;
use App\models\LoginForm;
use App\models\Role;
use App\models\Vendor;
use App\Repositories\UserRepository;
use App\repositories\VendorRepository;
use App\services\AuthService;
use App\services\EmailService;
use App\services\TokenService;

class VendorAuthController extends Controller
{
    private AuthService $authService;
    private EmailService $emailService;
    private UserRepository $userRepository;
    private VendorRepository $vendorRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->setLayout('auth');
        $this->authService = new AuthService();
        $this->emailService = new EmailService();
        $this->userRepository = new UserRepository();
        $this->vendorRepository = new VendorRepository();
        $this->registerMiddleware(new GuestMiddleware([
            'login', 'register'
        ]));
    }
    

    public function login(Request $request)
    {
        $loginForm = new LoginForm();
        
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            
            if ($loginForm->validate()) {
                if ($this->authService->login($loginForm->email, $loginForm->password, $loginForm->rememberMe ?? false, Role::VENDOR)) {
                    Application::$app->session->setFlash('success', 'Welcome back to your vendor dashboard!');
                    Application::$app->response->redirect('/vendor/dashboard');
                    return;
                }
            }
        }
        
        return $this->render('seller/auth/login', [
            'model' => $loginForm,
            'title' => 'Vendor Login'
        ]);
    }
    
    
    public function register(Request $request)
    {
        $user = new User();
        $vendor = new Vendor();
        
        if ($request->isPost()) {
            $userData = $request->getBody();
            $user->loadData($userData);
            $vendor->loadData($userData);
            
            Application::$app->db->pdo->beginTransaction();
            
            try {
                $existingUser = $this->userRepository->findByEmail($user->email);
                
                if ($existingUser) {
                    $existingUser->loadRoles();
                    
                    if ($existingUser->hasRole(Role::VENDOR)) {
                        $user->addError('email', 'This email is already registered as a vendor.');
                        throw new \Exception('User already has vendor role');
                    }
                    
                    $userId = $existingUser->id;
                    
                    if (!$this->authService->addRoleToUser($userId, Role::VENDOR)) {
                        throw new \Exception('Failed to add vendor role to user');
                    }
                } else {
                    if (!$user->validate()) {
                        throw new \Exception('User validation failed');
                    }
                    
                    $userId = $this->authService->register($userData, Role::VENDOR);
                    
                    if (!$userId) {
                        throw new \Exception('Failed to register user');
                    }
                }
                
                $vendorData = [
                    'user_id' => $userId,
                    'store_name' => $vendor->store_name,
                    'description' => $vendor->description,
                    'status' => 'pending' 
                ];
                
                $vendorId = $this->vendorRepository->create($vendorData);
                
                if (!$vendorId) {
                    throw new \Exception('Failed to create vendor profile');
                }
                
                // $this->notifyAdminAboutNewVendor($user, $vendor);
                
                Application::$app->db->pdo->commit();
                
                if ($existingUser) {
                    $this->authService->login($existingUser->email, $userData['password'], false, Role::VENDOR);
                    
                    Application::$app->session->setFlash('success', 'Vendor profile created successfully! Your account is pending approval by an administrator.');
                    Application::$app->response->redirect('/vendor/waiting-approval');
                } else {
                    Application::$app->session->setFlash('success', 'Thanks for registering as a vendor! Please check your email to verify your account. After verification, your account will be reviewed by an administrator.');
                    Application::$app->session->set('verification_email', $user->email);
                    Application::$app->response->redirect('/email-verification');
                }
                return;
            } catch (\Exception $e) {
                Application::$app->db->pdo->rollBack();
                Application::$app->session->setFlash('error', 'Registration failed: ' . $e->getMessage());
            }
        }
        
        return $this->render('seller/auth/register', [
            'userModel' => $user,
            'vendorModel' => $vendor,
            'title' => 'Become a Vendor'
        ]);
    }
   
    public function switchToBuyer()
    {
        $userData = Application::$app->session->get('user');
        
        if (!$userData) {
            Application::$app->response->redirect('/login');
            return;
        }
        
        if (!in_array(Role::BUYER, $userData['roles'])) {
            Application::$app->session->setFlash('error', 'You do not have a buyer account.');
            Application::$app->response->redirect('/vendor/dashboard');
            return;
        }
        
        $this->authService->switchActiveRole(Role::BUYER);
        
        Application::$app->session->setFlash('success', 'Switched to buyer account.');
        Application::$app->response->redirect('/');
    }
    
    
    public function switchToVendor()
    {
        $userData = Application::$app->session->get('user');
        
        if (!$userData) {
            Application::$app->response->redirect('/login');
            return;
        }
        
        if (!in_array(Role::VENDOR, $userData['roles'])) {
            Application::$app->session->setFlash('error', 'You do not have a vendor account.');
            Application::$app->response->redirect('/');
            return;
        }
        
        $this->authService->switchActiveRole(Role::VENDOR);
        
        Application::$app->session->setFlash('success', 'Switched to vendor account.');
        Application::$app->response->redirect('/vendor/dashboard');
    }

    public function waitingApproval()
    {
        $this->setLayout('auth');
        
        $userId = Application::$app->session->get('user')['id'] ?? 0;
        
        if (!$userId) {
            Application::$app->response->redirect('/login');
            return '';
        }
        
        $vendor = $this->vendorRepository->findByUserId($userId);
        
        if (!$vendor) {
            Application::$app->response->redirect('/');
            return '';
        }
        
        if ($vendor->status === 'active') {
            Application::$app->response->redirect('/vendor/dashboard');
            return '';
        }
        
        return $this->render('seller/auth/waiting-approval', [
            'vendor' => $vendor,
            'title' => 'Waiting For Approval'
        ]);
    }
}