<?php declare(strict_types=1);

namespace Bookstore\Controllers;

class OnlineBookController extends AbstractController{

    public function getAll(): mixed {
        $options = [
            'x-rapidapi-host' => $this->config->get('api')['host'],
            'x-rapidapi-key' => $this->config->get('api')['key'],
            'key' => $this->config->get('api')['key'],
        ];
        
        $res = $this->httpClient->request('GET', 'todos/1', $options);
        echo $res->getBody();
        exit;
    }
}