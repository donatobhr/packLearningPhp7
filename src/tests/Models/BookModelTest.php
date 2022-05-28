<?php declare(strict_types=1);

namespace Bookstore\Tests\Models;

use Bookstore\Models\BookModel;
use Bookstore\Tests\ModelTestCase;
use Bookstore\Domain\Book;
use Bookstore\Exceptions\DbException;

use ReflectionClass;

class BookModelTest extends ModelTestCase {
    protected $tables = ['borrowed_books', 'customer', 'book'];
    protected $model;

    public function setUp(): void {
        parent::setUp();
        $this->model = new BookModel($this->db);
    }

    protected function buildBook(array $properties): Book {
        $book = new Book();
        $reflectionClass = new ReflectionClass(Book::class);

        foreach ($properties as $key => $value) {
            $property = $reflectionClass->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($book, $value);
        }

        return $book;
    }

    protected function addBook(array $params) {
        $default = [
            'id' => null,
            'isbn' => 'isbn',
            'title' => 'title',
            'author' => 'author',
            'stock' => 1,
            'price' => 10.0
        ];

        $params = array_merge($default, $params);
        
        $query = <<<SQL
            insert into book (id, isbn, title, author, stock, price)
            values(:id, :isbn, :title, :author, :stock, :price)
        SQL;

        $this->db->prepare($query)->execute($params);
    }

    protected function addCustomer(array $params) {
        $default = [
            'id' => null,
            'firstname' => 'firstname',
            'surname' => 'surname',
            'email' => 'email',
            'type' => 'basic'
        ];

        $params = array_merge($default, $params);

        $query = <<<SQL
            insert into customer (id, firstname, surname, email, type)
            values(:id, :firstname, :surname, :email, :type)
        SQL;

        $this->db->prepare($query)->execute($params);
    }

    public function testBorrowBookNotFound() {
        $book = $this->buildBook(['id'=> 123]);
        $this->model->borrow($book, 123);
        $this->expectException(DbException::class);
    }

    public function testBorrow() {
        $book = $this->buildBook(['id'=> 123, 'stock' => 12]);
        $this->addBook(['id'=>123, 'stock' => 12]);
        $this->addCustomer(['id'=>123]);
        $this->model->borrow($book, 123);
    }
}