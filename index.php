<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Blog\PostMapper;
use Blog\LatestPosts;

require __DIR__ . '/vendor/autoload.php';

$loader = new Filesystemloader('templates');
$view = new Environment($loader);

//БД
$config = include 'config/database.php';
$dsn = $config['dsn'];
$username = $config['username'];
$password = $config['password'];

try {
    $connection = new PDO($dsn,$username,$password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    echo 'Database error: ' . $exception->getMessage();
    die();
}

$postMapper = new postMapper($connection);

//приложение
$app = AppFactory::create();
$app->setBasePath("/blog/php-blog");

$app->get('/', function (Request $request, Response $response)
    use ($view, $connection) {
    $latestPosts = new LatestPosts($connection);
    $posts = $latestPosts->get(3);

    $body = $view->render('index.twig', [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function ($request, $response, $args) use($view){
    $body = $view -> render('about.twig',[
        'name'=>'Ildar'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/{url_key}', function (Request $request, Response $response, $args) use ($view, $postMapper) {
    $post = $postMapper->getByUrlKey((string) $args['url_key']);

    if (empty($post)) {
        $body = $view->render('not-found.twig');
    } else {
        $body = $view->render('post.twig', [
            'post' => $post
        ]);
    }
    $response->getBody()->write($body);
    return $response;
});
$app->run();

?>