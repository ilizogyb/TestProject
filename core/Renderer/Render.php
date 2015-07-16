<?php

namespace Core\Renderer;

/**
 * Клас являє реалізацію рендера для візуалізації контента
 * @autor Lizogyb Igor
 * @since 1.0
 */
class Render {
	private $path = '';
    private $viewFiles = [];
    public $defaultExtension = 'php';

    public function __construct($path)
    {
        $this->path = $path;  
        $this->createTemplateList();
    
    }

    protected function createTemplateList()
    {
        $res = scandir($this->path);
        $files = array();
        foreach($res as $value) {
            if(!in_array($value, array('.', '..'))) {
               $files[$value] = $this->path . '/' .$value;             
            }
        }
        return $files;
    }

    public function render($view)
    {
        if(isset($this->createTemplateList()[$view . '.' . $this->defaultExtension])) {        
            require($this->createTemplateList()[$view . '.' . $this->defaultExtension]);
        }   
    }

}
?>
