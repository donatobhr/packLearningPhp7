<?php declare(strict_types=1);

namespace Bookstore\Tests\Domain\Customer;
use Bookstore\Domain\Customer\Basic;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase  {
    private $customer;

    public function setUp(): void {
        $this->customer = new Basic(1, 'han', 'solo', 'han@solo.com');
    }


    public function testAmountToBorrow() {
        Testcase::assertSame(3, $this->customer->getAmountToBorrow(), 'Basic customer should borrow up to 3 books.');
    }

    public function testIsExemptOfTaxes() {
        TestCase::assertFalse($this->customer->isExtentOfTaxes(), 'Basic customer should be exempt of taxes.');
    }

    public function testGetMonthlyFee() {
        TestCase::assertEquals(5, $this->customer->getMonthlyFee(), 'Basic customer should pay 5 a month.');
    }

}