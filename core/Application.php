<?php
/**
 * Modified Application.php with error handling integration (admin parts removed)
 */
namespace App\core;

use App\core\exception\ExceptionHandler;

/**
 * Class Application
 *
 * @package app
 */
class Application
{
    public static Application $app;
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public Database $db;
    public Session $session;
    public ?string $layout = 'main';
    protected bool $maintenanceMode = false;

    public function __construct($rootDir, array $config = [])
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        
        ExceptionHandler::register();
        
        $this->maintenanceMode = !empty($config['maintenance_mode']);
        
        if (!empty($config['db'])) {
            try {
                $this->db = new Database($config['db']);
            } catch (\Exception $e) {
                // Log the error but continue without database
                // to display a proper error page
                error_log($e->getMessage());
                if (!$this->isApiRequest()) {
                    $this->renderDatabaseErrorPage();
                    exit;
                }
            }
        }
    }

    public function run()
    {
        if ($this->maintenanceMode) {
            return $this->renderMaintenancePage();
        }
        
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }
    public function getController()
    {
        return $this->controller;
    }
    public function enableMaintenanceMode(): void
    {
        $this->maintenanceMode = true;
    }
    
    public function disableMaintenanceMode(): void
    {
        $this->maintenanceMode = false;
    }
    

    public function isInMaintenanceMode(): bool
    {
        return $this->maintenanceMode;
    }
    

    public function isApiRequest(): bool
    {
        $path = $this->request->getUrl();
        return strpos($path, '/api/') === 0;
    }
    
    protected function renderMaintenancePage(): string
    {
        $this->response->statusCode(503);
        
        header('Retry-After: 3600'); 
        
        return $this->router->renderView('errors/maintenance');
    }
    

    protected function renderDatabaseErrorPage(): string
    {
        $this->response->statusCode(500);
        return $this->router->renderView('errors/database');
    }
}