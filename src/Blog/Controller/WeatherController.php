<?php

namespace Blog\Controller;

use Blog\Model\Post;
use Core\Controller\Controller;
use Core\Request\Request;
use Core\Response\Response;

/**
 * Клас контроллер сторінки з погодою
 * @autor Lizogyb Igor
 * @since 1.0
 */
class WeatherController extends Controller
{
    /**
     * Базовий метод, дія яка виконується при показі
     * сторінки
     */
    public function indexAction()
    {
        echo "Hello from WeatherController";
        $this->render('weather.html');
    }

}
