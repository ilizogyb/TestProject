<?php

namespace Framework\Response;
/**
 * Клас реалізація HTTP Response
 * @autor Lizogyb Igor
 * @since 1.0
 *  
 */

class Response
{
    // HTTP статус коди і повідомлення	
    public static $httpStatuses = [
        // Інформаційні 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        118 => 'Connection timed out',
        
        // Успішні операції 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        210 => 'Content Different',
        226 => 'IM Used',
        
        // Перенаправлення 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',

        // Поилки клієнта 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',

		// Помилки сервера 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway or Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        507 => 'Insufficient storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];
    
    private $_statusCode = 200;
    private $_headers;
    protected $content = '';
    protected $_cookies = array();
    public $statusText = 'OK';
    public $version;
    protected $options = array();
		
	public function __construct()
	{
		if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
			$this->version = '1.0';
        } else {
           $this->version = '1.1';
        }
        // Встановлення можливості відправки HTTP заголовків, та кук
        $this->options['sendHeaders'] = true;
	}
	
    /**
    * Метод для отримання статус коду
    * @return рядок і значеням статус коду
    */
	public function getStatusCode()
    {
        return $this->_statusCode;
    }
    
    /**
    * Метод для встановлення статус коду
    * @param int $value тризначне число із значенням коду
    * @param string $text рядок із поясненням значення коду
    */
    public function setStatusCode($value, $text = null)
    {
		if ($value === null) {
            $value = 200;
        }
         $this->_statusCode = (int) $value;

        if ($text === null) {
			 $this->statusText = isset(static::$httpStatuses[$this->_statusCode]) ? static::$httpStatuses[$this->_statusCode] : '';
        } else {
			$this->statusText = $text;
		}
	}

    /**
    * Метод для нормалізації  заголовків
    * @param string $name назва заголовку
    * @return string нормалізований заголовок
    */
    protected function normalizeHeaderName($name)
    {
        return preg_replace_callback(
                  '/\-(.)/', 
                  function ($matches) {
                    return '-'.strtoupper($matches[1]);
                  }, 
                  strtr(ucfirst(strtolower($name)), '_', '-')
        );
    }
	
    /**
    * Метод для отримання поточного значення заголовку
    * @param string $name назва заголовку
    * @return string рядок із значенням заголовку
    */
    public function getHeader($name)
    {
        $name = $this->normalizeHeaderName($name);

        return isset($this->_headers[$name]) ? $this->_headers[$name] : null;
    }

    /**
    * Метод для отримання заголовків з поточного response
    * @return масив із рядками заголовків
    */
    public function getHeaders()
    { 
		return $this->_headers;
	}
   
   /**
   * Метод для встановлення HTTP заголовків.
   * @param string  $name ім'я заголовку
   * @param string  $value    значення(вставіть null для видалення заголовку)
   * @param bool    $replace  зміна значення
   */
	public function setHeader($name, $value, $replace = true)
    {
        $name = $this->normalizeHeaderName($name);
        //Знищуємо заголовок якщо значеня відсутнє
        if ($value == null) {
            unset($this->headers[$name]);
            return;
        }

        if($name == 'Content-Type') 
        {
            if ($replace || !$this->getHeader('Content-Type')) {
                $this->setContentType($value);
            }
            return;
        }

        if (!$replace)
        {
           $current = isset($this->headers[$name]) ? $this->headers[$name] : '';
           $value = ($current ? $current.', ' : '').$value;
        }

        $this->_headers[$name] = $value;
    }

   /**
   * Метод для типу контенту для поточного response.
   * @param string  $value    рядок із значенням типу
   */
    public function setContentType($value)
    {
        $this->_headers['Content-Type'] = $value;
    }
   
   /**
   * Метод для отримання типу контенту для поточного response.
   * @retutn string  рядок із значенням типу контенту
   */
    public function getContentType()
    {
         return $this->_headers['Content-Type'];
    }

   /**
   * Метод для отримання контенту поточного response.
   * @retutn string  контент поточного response
   */
    public function getContent()
    {
        return $this->content;
    }

   /**
   * Метод для встановлення контенту поточного response.
   * @param $content string  контент поточного response
   */   
    public function setContent($content)
    {
        if($content != null && strlen($content) > 0) {
            $this->content .= (string) $content;
        }    
    }
   
   /**
   * Метод для встановлення кук поточного sponse.
   * @param string $name ім'я заголовку
   * @param string $value значення куки
   * @param string $expire час життя куки
   * @param string $path шлях
   * @param  string  $domain    ім'я домену
   * @param  bool    $secure    якщо використовується захист
   * @param  bool    $httpOnly  Якщо використовується тільки  HTTP
   */
    public function setCookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false, $httpOnly = false) 
    {
       if($expire != null)
        {
            if(is_numeric($expire))
            {
                  $expire = (int) $expire + time();
            } else {
                $expire = strtotime($expire);
            }      
        }
        
        if($name != null && $value != null) 
        {
            $this->_cookies[$name] = array(
              'name'     => $name,
              'value'    => $value,
              'expire'   => $expire,
              'path'     => $path,
              'domain'   => $domain,
              'secure'   => $secure ? true : false,
              'httpOnly' => $httpOnly,
            );
        } 
    }

    /**
    * Метод для отимання масиву кук поточного response.
    * @return string масив з куками поточного response
    *
    */
    public function getCookies()
    {
        return $this->_cookies;
    }

    /**
    * Метод для видалення куки з поточного response.
    * @param $name string ім'я заголовку
    * @return булеве значення хибності  якщо кука не втдалилась
    * або Response
    *
    */
    public function delCookie($name)
    {
        if($name != null && strlen($name) > 0) {        
            unset($this->_cookies[$name]);
            return $this;
        }
        return false;    
    }

    /**
    * Метод для видалення всіх кук з поточного response.
    * @return Response
    *
    */
    public function delCookies() 
    {
        $this->_cookies = array();
        return $this;
    }

    /**
    * Метод для відправки заголовків і кук в HTTP клієнт, посилає заголовки і куки
    * тільки один раз, наступні виклики методу не виконуватимуть ніяких дій
    */   
    protected function sendHeaders()
    {
        //заголовки
        if(count($this->_headers) === 0 || !$this->options['sendHeaders']) {
            return;
        }
 
        $statusCode = $this->getStatusCode();
        $ver = $this->version;
        header("HTTP/{$ver} $statusCode {$this->statusText}");

       foreach($this->_headers as $name => $value) {
            header($name.': '.$value);
       }

        $this->sendCookies();
        //Запобігання повторній пересилці заголовків
        $this->options['sendHeaders'] = false;
    }

    /**
    * Метод для відправки кук в HTTP клієнт 
    *
    */
    protected function sendCookies() 
    {
         // ітерація і встановлення куків
        foreach ($this->_cookies as $cookie)
        {            
             setcookie($cookie['name'], $cookie['value'], $cookie['expire'], 
                  $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httpOnly'] );
        }
    }

    /**
    * Метод для відправки заголовків та контенту в HTTP клієнт 
    *
    */
    public function send()
    {
        $this->sendHeaders();
        echo  $this->getContent();
    }

    /**
     * Візуалізація статусного рядка
     * @return string
     */
    public function renderStatusLine()
    {
       $status = sprintf(
           'HTTP/%s %d %s',
            $this->version,
            $this->getStatusCode(),
            $this->statusText
        );
        return trim($status);
    }

    /**
     * Метод для відображення response у вигляді рядка
     *
     * @return string
     */
    public function __toString()
    {
        $str  = $this->renderStatusLine() . "<br>";
        foreach($this->getHeaders() as $name => $value)
        {
             $str .= "{$name}: " . "{$value}" . "<br>";;
        }
        $str .= "<br>";
        $str .= $this->getContent();
        return $str;   
    }    
}
?>
