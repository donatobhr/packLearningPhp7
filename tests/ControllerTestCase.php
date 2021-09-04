<?php declare(strict_types=1);

namespace Bookstore\Tests;

use Bookstore\Utils\DependencyInjector;
use Bookstore\Core\Config;
use Monolog\Logger;
use Twig\Environment;
use PDO;


abstract class ControllerTestCase extends AbstractTestCase {

    protected $di;

    public function setUp(): void {
        $this->di = new DependencyInjector();
        $this->di->set('PDO', $this->mock(PDO::class));
        $this->di->set('Utils\Config', $this->mock(Config::class));
        $this->di->set('Twig_Enviroment', $this->mock(Environment::class));
        $this->di->set('Logger', $this->mock(Logger::class));
    }


}