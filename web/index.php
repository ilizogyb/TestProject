<?php
require_once(__DIR__.'/../core/Loader.php');

$loader = Loader::getInstance();
$loader->addNamespacePath('Blog\\',__DIR__.'/../src/Blog');
$loader->addNamespacePath('Core\\',__DIR__.'/../core');
$loader->register();

$app = new \Core\Application(__DIR__.'/../app/config/routes.php');
$app->run();
