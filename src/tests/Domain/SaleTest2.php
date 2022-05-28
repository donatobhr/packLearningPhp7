<?php declare(strict_types=1);

namespace Bookstore\Tests\Domain;

use Bookstore\Domain\Sale as Sale2;
use PHPUnit\Framework\TestCase;

class SaleTest2 extends TestCase {

    public function testCanCreate() {
        $sale = new Sale2();
        $this->assertInstanceOf(Sale2::class, $sale, 'It should be of type Sale 2');
    }

    public function testWhenCreatedBookListIsEmpty() {
        $sale = new Sale2();
        $this->assertEmpty($sale->getBooks());
    }

    public function testWhenAddingABookIGetOneBook() {
        $sale = new Sale2();
        $sale->addBook(123);

        $this->assertSame($sale->getBooks(), [123=>1]);
    }

    public function testSpecifyAmountBooks() {
        $sale = new Sale2();
        $sale->addBook(123, 5);
        $this->assertSame($sale->getBooks(), [123 => 5]);
    }


    public function testAddMultipleTimesSameBook() {
        $sale = new Sale2();
        $sale->addBook(123, 5);
        $sale->addBook(123);
        $sale->addBook(123, 5);

        $this->assertSame($sale->getBooks(), [123 => 11]);
    }


    public function testAddDifferentBooks() {
        $sale = new Sale2();
        $sale->addBook(123, 5);
        $sale->addBook(456, 2);
        $sale->addBook(789, 5);

        $this->assertSame($sale->getBooks(), [123 => 5, 456 => 2, 789 => 5]);
    }
}