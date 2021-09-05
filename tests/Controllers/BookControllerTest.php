<?php declare(strict_types=1);

namespace Bookstore\Tests\Controllers;

use Bookstore\Controllers\BookController;
use Bookstore\Core\Request;
use Bookstore\Tests\ControllerTestCase;
use Bookstore\Exceptions\NotFoundException;
use Bookstore\Models\BookModel;


class BookControllerTest extends ControllerTestCase {


    private function getController(Request $request = null) {
        if($request === null) {
            $request = $this->mock(Request::class);
        }
        return new BookController($this->di, $request);
    }

    public function testBookNotFound() {
        $bookModel = $this->mock("\Bookstore\Models\BookModel");
        $bookModel
            ->expects($this->once())
            ->method('get')
            ->with(123)
            ->will(
                $this->throwException(new NotFoundException())
            );
        $this->di->set('BookModel', $bookModel);

        $response = '';
        $template = $this->mock("\Twig\TemplateWrapper");

        $this->di->get('Twig_Enviroment')
            ->expects($this->once())
            ->method('load')
            ->with('error.twig')
            ->willReturn($template);

        $result = $this->getController()->borrow(123);
        
        $this->assertSame($result, $response, 'Response object is not the expected one.');
        
    }
}