<?php declare(strict_types=1);

namespace Bookstore\Tests;

use Bookstore\Core\Config;
use PDO;

abstract class ModelTestCase extends AbstractTestCase {
    protected $db;
    protected $tables = [];

    public function setUp(): void{
        $config = new Config();
        $dbConfig = $config->get('db');
        $this->db = new PDO('mysql:host=192.168.33.10;dbname=bookstore', $dbConfig['user'], $dbConfig['password']);
        $this->db->beginTransaction();
        $this->cleanAllTables();
    }

    public function tearDown(): void {
        $this->db->rollBack();
    }

    protected function cleanAllTables() {
        foreach($this->tables as $table) {
            $this->db->exec("delete from $table");
        }
    }
}