<?php declare(strict_types=1);

namespace Bookstore\Controllers;

use Bookstore\Core\Request;
use Bookstore\Utils\DependencyInjector;


abstract class AbstractController {
    protected $request;
    protected $db;
    protected $config;
    protected $view;
    protected $log;

    public function __construct(DependencyInjector $di, Request $request) {
        $this->request = $request;
        $this->di = $di;

        $this->db = $this->di->get('PDO');
        $this->config = $this->di->get('Utils\Config');
        $this->log = $this->di->get('Logger');
        $this->view = $this->di->get('Twig_Enviroment');
        $this->customerId = $request->getCookies()->get('id');
    }

    protected function render(string $template, $params): string {
        return $this->view->load($template)->render($params);
    }

    public function setCustomerId($customerId) {
        $customerId = (int) $customerId;
        $this->customerId = $customerId;
    }
}
