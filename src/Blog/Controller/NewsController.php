<?php

namespace Blog\Controller;

use Blog\Model\Post;
use Core\Controller\Controller;
use Core\Request\Request;
use Core\Response\Response;

/**
 * Клас контроллер сторінки новин
 * @autor Lizogyb Igor
 * @since 1.0
 */
class NewsController extends Controller
{
    /**
     * Базовий метод, дія яка виконується при показі
     * сторінки
     */
    public function indexAction()
    {
        echo "Hello from NewsController";
        $this->render('news.html');
    }
    
    /**
     * Метод для виводу привітання на сторінці новин
     * Використовував як тестовий, для тесту викклику 
     * з іншого контроллера
     */
    public function writeGreatingsAction()
    {
        echo "Wellcome to the news page!";    
    }

}
