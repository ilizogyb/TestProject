<?php

return array(
    'home'           => array(
        'pattern'    => '/',
        'controller' => 'Blog\\Controller\\PostController',
        'action'     => 'index'
    ),
    'testredirect'   => array(
        'pattern'    => '/news',
        'controller' => 'Blog\\Controller\\NewsController',
        'action'     => 'index',
    ),
    'test_json' => array(
        'pattern'    => '/weather',
        'controller' => 'Blog\\Controller\\WeatherController',
        'action'     => 'index',
    )

);
