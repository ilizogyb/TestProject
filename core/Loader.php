<?php


/**
* Реалізація автозавантаження класів
* @autor Lizogyb Igor
* @since 1.0
*/
class Loader
{
	/**
    * Статична змінна в якій ми будемо зберігати
    * Екзаемпляр класу
    *
    */
    protected static $_instance;
	
    //карта для відповідності неймспейсу шляху в файловій системі
    protected $namespacesMap = array();
    
	/**
    * Закриваємо доступ до функції поза класом.
    *
    */
	private function __construct(){}
	
	/**
    * Закриваємо доступ до функції поза класом.
    *
    */
	private function __clone(){}
    
    /**
    * Статична функція, яка повертає
    * екземпляр класу або створює новий за
    * необхідності
    *
    * @return Loader
    */
	public static function getInstance()
	{
		if(null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	* Реєстрація власного автозавантажувача в стек автозавантаження
	*
	*/
    public function register()
    {
        spl_autoload_register(array($this,'loadClass'));
    }
	
    /**
	* Видалення власного автозавантажувача із стеку автозавантаження
	*
	*/
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));    
    }
	
    /**
	* Встановлення шляху простору імен
	* @param string $namespace рядок із значенням простору імен
	* @param string $rootDir рядок із значенням шляху
	* @return булеве значення істиності або хибності в залежності від результату роботи методу
	*/
    public function addNamespacePath($namespace, $rootDir)
    {
        if (is_dir($rootDir)) {
			$namespace = trim($namespace, '\\');
            $this->namespacesMap[$namespace] = $rootDir;
            return true;
        }
        
        return false;
    }
	
    /**
	* Завантаження потрібного класу
	* @param string $class ім'я класу для завантаження
	* @return булеве значення істиності або хибності в залежності від результату роботи методу
	* @throws ClassNotFoundException якщо клас не знайдено
	*/
    protected function loadClass($class)
    {
        $pathParts = explode('\\', $class);
        if(is_array($pathParts)) {
            $namespace = array_shift($pathParts);
            if (!empty($this->namespacesMap[$namespace])) {
                $filePath = $this->namespacesMap[$namespace] . '/' . implode('/', $pathParts) . '.php';
				if(file_exists($filePath)) {
					require_once $filePath;
				} else {
                    //This is test project
                    }
                return true;
            } else {
				
			}
        }
        return false;
    }
}

