<?php

namespace Core\Controller;
use \Core\Renderer\Render;
use \Core\Application;
use \Core\Request\Request;

abstract class Controller
{
	public $model;
	private $view;
    private $request;

    public function __construct()
    {
        $this->request = Application::getRequest();
    }
    
    public function render($view)
    {
        $render = Application::getRender();
        $render->render($view);
    }
    
  
    public function getRequest()
    {
        return $this->request;
    }
    
    
    public function redirect($route, $message = null)
    {
       // echo $route;
       // header("Location: ".$route, true, 301);
    }
    
    /**
     * Метод для отримання поточного роута
     * @return string рядок з роутом
     */
    public function getRoute()
    {
        echo $this->request->getPathInfo();
    }

    public function generateRoute($route)
    {
        echo $route;
    }
    
    /**
     * Метод для виклику контроллера і передачі йому параметрів
     * HMVC
     *
     */
    public function get($path, $param = [])
    {  
        echo "<br>Call to other Controller (HMVC)<br>";        
        Application::runController($path, $param);
    }


}

