<?php

namespace Blog\Controller;

use Blog\Model\Post;
use Core\Controller\Controller;
use Core\Request\Request;
use Core\Response\Response;

/**
 * Клас контроллер головної сторінки
 * @autor Lizogyb Igor
 * @since 1.0
 */
class PostController extends Controller
{
    /**
     * Базовий метод, дія яка виконується при показі
     * сторінки
     */
    public function indexAction()
    {
        echo "Hello from PostController";
        $this->render('post.html');
        /* виклик іншого контроллера з поточного і передача дії         
           яку потрібно виконати                    
        */
        $this->get('news', ['action'=>'writeGreatings']);
        
    }

}
