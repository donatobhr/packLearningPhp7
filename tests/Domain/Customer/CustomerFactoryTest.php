<?php declare(strict_types=1);

namespace Bookstore\Tests\Domain\Customer;
use Bookstore\Domain\Customer\Basic;
use Bookstore\Domain\Customer\Premium;
use Bookstore\Domain\Customer\CustomerFactory;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class CustomerFactoryTest extends TestCase {
    public function testFactoryBasic() {
        $customer = CustomerFactory::factory('basic', 1, 'han', 'solo', 'han@solo.com');
        $expectedBasicCustomer = new Basic(1, 'han', 'solo', 'han@solo.com');

        TestCase::assertEquals($customer, $expectedBasicCustomer, 'basic should create a Customer\Basic.');
    }

    
    public function testCreatingWrongTypeOfCustomer() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Wrong type");
        $customer = CustomerFactory::factory('deluxe', 1, 'han', 'solo', 'han@solo.com');
    }


    /**
     * @dataProvider providerFactoryValidCustomerTypes
     * @param string $type
     * @param string $expectedType
     */
    public function testFactoryValidCustomerTypes(string $type, string $expectedType) {
        $customer = CustomerFactory::factory($type, 1, 'han', 'solo', 'han@solo.com');
        $this->assertInstanceOf($expectedType, $customer, 'Factory created the wrong type of customer');
    }

    public function providerFactoryValidCustomerTypes() {
        return [
            'Basic customer, lowercase' => [
                'type' => 'basic',
                'expectedType' => Basic::class
            ],
            'Basic customer, uppercase' => [
                'type' => 'BASIC',
                'expectedType' => Basic::class
            ],
            'Premiun customer, lowercase' => [
                'type' => 'premium',
                'expectedType' => Premium::class
            ],
            'Premium customer, uppercase' => [
                'type' => 'PREMIUM',
                'expectedType' => Premium::class
            ]
        ];
    }
}