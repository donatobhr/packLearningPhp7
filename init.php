<?php

declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

use Bookstore\Utils\Config;

$dbConfig = Config::getInstance()->get('db');

$db = new PDO(
    "mysql:host=localhost;dbname=bookstore",
    $dbConfig['user'],
    $dbConfig['password']
);


$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// $query = <<<SQL
//     INSERT INTO book (isbn, title, author, price)
//     VALUES ("9788187981954", "Peter Pan", "J. M. Barrie", 2.34)
// SQL;

// $result = $db->exec($query);

// if($result) {
//     $rows = $db->query('SELECT * FROM book ORDER BY title');
//     foreach($rows as $row) {
//         var_dump($row['author']);
//     }
// }else {
//     $error = $db->errorInfo();
//     var_dump($error);
// }


// $query = 'SELECT * FROM book WHERE author = :author';
// $statement = $db->prepare($query);
// $statement->bindValue('author', 'George Orwell');
// $statement->execute();
// $rows = $statement->fetchAll();

// $query = <<<SQL
//     INSERT INTO book(isbn, title, author, price)
//     VALUES (:isbn, :title, :author, :price)
// SQL;
// $statement = $db->prepare($query);
// $params = [
//     'isbn' => '9781412108614',
//     'title' => 'Iliad',
//     'author' => 'Homer',
//     'price' => 9.25
// ];

// $statement->execute($params);
// echo($db->lastInsertId());

//     $rows = $db->query('SELECT COUNT(*) AS TOTAL FROM book ORDER BY title');
//     foreach($rows as $row) {
//         var_dump($row['TOTAL']);
//     }


function addBook(int $id, int $amount = 1) : void {
    $db = $GLOBALS['db'];
    $query = 'UPDATE book SET stock = stock + :n WHERE id = :id';
    $statement = $db->prepare($query);
    $statement->bindValue('id', $id);
    $statement->bindValue('n', $amount);

    if(!$statement->execute()) {
        throw new Exception($statement->errorInfo()[2]);
    }

}

function addSale(int $userId, array $booksIds) : void {
    $db = $GLOBALS['db'];
    $db->beginTransaction();

    try {
        $query = 'INSERT INTO sale (customer_id, date) VALUES(:id, NOW())';
        $statement = $db->prepare($query);
        if(!$statement->execute(['id' => $userId])) {
            throw new Exception($statement->errorInfo()[2]);
        }

        $saleId = $db->lastInsertId();

        $query = "INSERT INTO sale_book (book_id, sale_id) VALUES(:book, :sale)";
        $statement = $db->prepare($query);
        $statement->bindValue('sale', $saleId);

        foreach($booksIds as $bookId) {
            $statement->bindValue('book', $bookId);
            if(!$statement->execute()) {
                throw new Exception($statement->errorInfo()[2]);
            }
        }

        $db->commit();
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
}


try {
    addSale(1, [1,2,3]);
} catch (Exception $e) {
    echo 'Error adding sale: ' . $e->getMessage();
}