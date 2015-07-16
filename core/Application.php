<?php
namespace Core;

use Core\Request\Request;
use Core\Router\Router;
use Core\Renderer\Render;

/**
* Application головний клас додатку
* створює об'єкти, запускає маршрутизацію, вмикає потрібні контроллери
* @autor Lizogyb Igor
* @since 1.0
*/
class Application
{
    const VIEW_PATH = '/src/Blog/Views';    
    protected static $request;
    protected static $router;
    protected $config;

    /**
     * Метод ініціалізації додатку
     * @param array $param масив з параметрами в даному випадку використовується 
     * масив з роутами
     *
     */
    public function __construct($param)
    {
        if(file_exists($param) && is_readable($param)) {
			$this->config = include($param);
		}
        
        self::$request = new Request();
        self::$router = new Router(self::$request, $this->config);
    }

    /**
     * Метод запуску додатку
     */
    public function run()
    {
        
    $controllerName = self::$router->getController();
    $controller = new  $controllerName;
    
    $action = self::$router->getAction()[0] . 'Action';    
    call_user_func(array($controller,  $action));
    }

    /**
     * Метод отримання поточного реквеста
     * @return Request інстанс поточного реквесту
     *
     */
    public static function getRequest()
    {
        return self::$request;    
    }
    
    /**
     * Метод для виклику потрібного контроллера і його методів
     * Якщо список параметрів порожній то створюється об'єкт потрібного
     * контроллера і передається в місце виклику
     * @param string $uri роут портрібного контроллера, наприклад 'news'
     * @param array string $param масив з параметрами, наприклад ['action'=>'index']
     * @return Об'єкт потрібного контроллера
     */
    public static function runController($uri, $param = [])
    {
        $req = new Request($uri);
        self::$router->setUri($req);
        $controllerName = self::$router->getController();
        $controllerObj = new $controllerName();

        if (array_key_exists('action', $param)) {
            $controllerAction = $param['action'] . 'Action';
            call_user_func(array($controllerObj, $controllerAction));
        } else {
            return $controllerObj;
        }
    }
    
    /**
     * Метод для отримання поточного рендера із шляхом до
     * папки з шаблонами, який задається через константу VIEW_PATH
     * @return Render поточний рендер
     */
    public static function getRender()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . self::VIEW_PATH;        
        return new Render($path);    
    }
    
}
