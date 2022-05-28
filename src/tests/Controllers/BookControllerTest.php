<?php declare(strict_types=1);

namespace Bookstore\Tests\Controllers;

use Bookstore\Controllers\BookController;
use Bookstore\Core\Request;
use Bookstore\Tests\ControllerTestCase;
use Bookstore\Exceptions\NotFoundException;
use Twig\Template;
use Bookstore\Models\BookModel;
use Bookstore\Domain\Book;
use Bookstore\Exceptions\DbException;

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


    protected function mockTemplate(string $templateName, array $params, $response) {
        $template = $this->mock(Template::class);
        $template
            ->expects($this->once())
            ->method('render')
            ->with($params)
            ->willReturn($response);

        $this->di->get('Twig_Enviroment')
            ->expects($this->once())
            ->method('loadTemplate')
            ->with($templateName)
            ->willReturn($template);
    }

    public function testNotEnoughtCopies() {
        $bookModel = $this->mock(BookModel::class);

        $bookModel
            ->expects($this->once())
            ->method('get')
            ->with(123)
            ->willReturn(new Book());
        
        $bookModel
            ->expects($this->never())
            ->method('borrow');

        $this->di->set('BookModel', $bookModel);

        $response = '';
        $this->mockTemplate('error.twig', ['errorMessage' => 'There are no copies left'], $response);

        $result = $this->getController()->borrow(123);

        $this->assertSAme($result, $response, 'Response object is not the expected one.');
    }


    public function testErrorSaving() {
        $controller = $this->getController();
        $controller->setCustomerId(9);
        
        $book = new Book();
        $book->addCopy();
        $bookModel = $this->mock(BookModel::class);
        $bookModel
            ->expects($this->once())
            ->method('get')
            ->with(123)
            ->willReturn($book);

        $bookModel
            ->expects($this->once())
            ->method('borrow')
            ->with(new Book(), 9)
            ->will($this->throwException(new DbException()));

        $this->di->set('BookModel', $bookModel);

        $response = "Rendered template";
        $this->mockTemplate('error.twig', ['errorMessage' => 'Error borrowing book.'], $response);

        $result = $controller->borrow(123);
        $this->assertSame($result, $response, 'Response object is not the expected one.');
    }

    public function testBorrowingBook() {
        $controller = $this->getController();
        $controller->setCustomerId(9);

        $book = new Book();
        $book->addCopy();
        $bookModel = $this->mock(BookModel::class);

        $bookModel
            ->expects($this->once())
            ->method('get')
            ->with(123)
            ->willReturn($book);

        $bookModel
            ->expects($this->once())
            ->method('borrow')
            ->with(new Book(), 9);
        
        $bookModel
            ->expects($this->once())
            ->method('getByUser')
            ->with(9)
            ->willReturn(['book1', 'book2']);

        $this->di->set('BookModel', $bookModel);
        
        $response = '';
        
        $this->mockTemplate('book.twig', 
            [
                'books' => ['book1', 'book2'],
                'currentPage' => 1,
                'lastPage' => true
            ],
            $response
        );

        $result = $controller->borrow(123);
        $this->assertSame($result, $response, 'Response object is not the expected one.');
    }
}