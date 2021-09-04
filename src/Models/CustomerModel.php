<?php declare(strict_types=1);

namespace Bookstore\Models;

use Bookstore\Domain\Customer;
use Bookstore\Domain\Customer\CustomerFactory;
use Bookstore\Exceptions\NotFoundException;

class CustomerModel extends AbstractModel {
    
    public function get(int $userId): Customer {
        $query = "SELECT * FROM customer WHERE customer_id = :user";
        $sth = $this->db->prepare($query);
        $sth->execute(['user' => $userId]);

        $row = $sth->fetch();

        if(empty($row)) {
            throw new NotFoundException();
        }

        return CustomerFactory::factory(
            $row['type'],
            $row['id'],
            $row['firstname'],
            $row['surname'],
            $row['email']
        );
    }


    public function getByEmail(string $email): Customer {
        $query = "SELECT * FROM customer WHERE email = :user";
        $sth = $this->db->prepare($query);
        $sth->execute(['user' => $email]);
        $row = $sth->fetch();
        
        if(empty($row)) {
            throw new NotFoundException();
        }

        return CustomerFactory::factory(
            $row['type'],
            (int) $row['id'],
            $row['firstname'],
            $row['surname'],
            $row['email']
        );
    }
}