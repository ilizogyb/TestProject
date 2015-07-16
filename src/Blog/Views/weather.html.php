<!DOCTYPE html> 
<html>
    <head>
        <title>Weather Page</title>
    </head>
    <body>
        <h2>Hello from Weather Page</h2>
        <!-- Виклик контроллера із шаблону -->
        <?php use \Core\Application; ?>    
        <?php Application::runController('news')->render('news.html'); ?>  
    </body>
</html>
