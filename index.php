<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Slim\Factory\AppFactory;
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;

    require __DIR__ . '/vendor/autoload.php';

    $loader = new Filesystemloader('templates');
    $view = new Environment($loader);

    $app = AppFactory::create();
    $app->setBasePath("/blog/php-blog");

    $app->get('/', function (Request $request, Response $response, $args) use($view){
        $body = $view -> render('index.twig');
        $response->getBody()->write($body);
        return $response;
    });
    $app->get('/about', function (Request $request, Response $response, $args) use($view){
        $body = $view -> render('about.twig',[
            'name'=>'Ildar'
        ]);
        $response->getBody()->write($body);
        return $response;
    });

    $app->run();
?>