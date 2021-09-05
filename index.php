<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
use Bookstore\Core\Request;
use Bookstore\Core\Router;
use Bookstore\core\Config;
use Bookstore\Models\BookModel;
use Bookstore\Utils\DependencyInjector;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


require_once __DIR__ . '/vendor/autoload.php';
$config = new Config();

$dbConfig = $config->get('db');
$db = new PDO('mysql:host=127.0.0.1;dbname=bookstore',$dbConfig['user'],$dbConfig['password']);

$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/src/views');
$view = new Twig\Environment($loader);

$log = new Logger('bookstore');
$logFile = $config->get('log');
$log->pushHandler(new StreamHandler($logFile, Logger::DEBUG));

$di = new DependencyInjector();
$di->set('PDO', $db);
$di->set('Utils\Config', $config);
$di->set('Twig_Enviroment', $view);
$di->set('Logger', $log);
$di->set('BookModel', new BookModel($di->get('PDO')));

$router = new Router($di);
$response = $router->route(new Request());
echo $response;
