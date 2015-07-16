<?php

namespace Core\Router;

use \Core\Request\Request;


/**
* Клас Router являється реалізацією маршрутизатора
* @autor Lizogyb Igor
* @since 1.0
*/
class Router 
{

    const DEFAULT_CONTROLLER = "Blog\\Controller\\PostController";
	const DEFAULT_ACTION     = "index";
	
	protected $controller    = self::DEFAULT_CONTROLLER;
    protected $action        = array();
	protected $method        = '';
	protected $security      = '';
	protected $id            = '';
	protected $basePath      = "/";
    protected $path          = '';
    protected $options       = array();

	public function __construct(Request $req, $options)
	{
		$this->path = $req->getPathInfo();
        $this->options = $options;
        $this->parseUri($options);
	}
	
    public function setUri(Request $req)
	{
        $this->path = $req->getPathInfo();
        $this->parseUri($this->options);
	}
    
	/** 
	* Метод для отримання URI
	* @ return масив з частинами URI
	*/
	protected function getUri()
	{
        return explode('/', $this->path);
	}
	
	/** 
	* Метод для розбору URI
	* @param array $data_array масив з конфігурацією маршрутів та їх
	* властивостей(параметрів)
	*/
	protected function parseUri(array $data_array)
	{
		$uri_array = $this->getUri();
        foreach($data_array as $data) {
          $result = ltrim($data['pattern'],'/');
          $result = explode('/', $result);

			//Обробка URI виду /
			if(strlen($uri_array[0]) === 0) {
                $this->controller = self::DEFAULT_CONTROLLER;
                array_push($this->action,self::DEFAULT_ACTION);
				break;
			}
            
            //Обробка URI виду /resource
			if(count($uri_array) === 1 && count($result) === 1) {
				if($uri_array[0] == $result[0]) {
					$this->controller = $data['controller'];
					array_push($this->action, $data['action']);
					//Обообка вкладених опцій
					if(isset($data['_requirements']) && is_array($data['_requirements']))
					{
						foreach($data['_requirements'] as $k=>$v) {
							if($k === '_method') {
								$this->method = $v;
							}
							if($k === 'id') {
								$this->id = $v;
							}
						}
					}
				}
			}
            
            //Обробка URI виду /resource/resource1
			if(count($uri_array) === 2 && count($result) === 2) {
                if($uri_array[0] == $result[0]) {
                    if (!preg_match_all("/[^\d+$]/", $uri_array[1])) {
                        $id = $uri_array[1];
                        $uri_array[1] ='{id}';
                    } 
                    
                    if($uri_array[1] == $result[1]) {
                       
                       $this->controller = $data['controller'];
					   array_push($this->action, $data['action']);

                        //Обробка вкладених опцій
					    if(isset($data['security']) && is_array($data['security'])) {
						    $this->security = $data['security'][0];
					    }
                        
                        if(isset($data['_requirements']) && is_array($data['_requirements']))
                        {
                            foreach($data['_requirements'] as $k=>$v) {
                                if($k === 'id') {
								$this->id = $id;
                                }
                            }
                        }
                    } 
                }                
            }
            
            //Обробка URI виду /resource/resource1/resource2
			if(count($uri_array) === 3 && count($result) === 3) {
                if($uri_array[0] == $result[0]) {
                    if (!preg_match_all("/[^\d+$]/", $uri_array[1])) {
                        $id = $uri_array[1];
                        $uri_array[1] ='{id}';
                    }
                    
                    if($uri_array[0] == $result[0] && $uri_array[1] == $result[1] && $uri_array[2] == $result[2]) {
                        $this->controller = $data['controller'];
					    array_push($this->action, $data['action']);
					    //Обробка вкладених опцій
                        if(isset($data['_requirements']) && is_array($data['_requirements']))
                        {
                            foreach($data['_requirements'] as $k=>$v) {
                                if($k === 'id') {
                                    $this->id = $id;
                                }
                                if($k === '_method') {
                                    $this->method = $v;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        //Якщо не відбулось співпадань
        if(is_array($this->action) && empty($this->action)){
           $this->controller = self::DEFAULT_CONTROLLER;
           array_push($this->action, self::DEFAULT_ACTION);
        }
	}
	
	/**
	* Отримання контроллера
	* @return string рядок із значенням контроллера
	*/
	public function getController()
	{
		return $this->controller;
	}
	
	/**
	* Отримання дії
	* @return string масив з рядками із значенням дій
	*/	
	public function getAction()
	{
		return $this->action;
	}
	
	/**
	* Отримання методу
	* @return string рядок із значенням методу або булеве 
	* значення хибності якщо метод не існує
	*/
	public function getMethod()
	{
		if(strlen($this->method) > 0)
			return $this->method;
		else 
			return false;
	}
	
	/**
	* Отримання Id посту
	* @return  string рядок із значенням Id посту або булеве 
	* значення хибності якщо метод не існує
	*/	
	public function getId()
	{
		if(strlen($this->id) > 0)
			return $this->id;
		else
			return false;
	}
	
	/**
	* Отримання властивостей безпеки
	* @return string рядок із значенням властивостей безпеки або булеве 
	* значення хибності якщо метод не існує
	*/
	public function getSecurity()
	{
		if(strlen($this->security) > 0)
			return $this->security;
		else
			return false;
	}
	
	/**
	* Метод вмикання потрібного контроллера
	*/
	function run()
	{
		// Подключаем файл контроллера, если он имеется
        
        $controllerPath =  $_SERVER['DOCUMENT_ROOT'] .'/src/' . $this->controller.'.php';
		if(file_exists($controllerPath)){
          include($controllerPath);
			echo $controllerPath;
        } else {
            //This is test project
	    }
	}
}

