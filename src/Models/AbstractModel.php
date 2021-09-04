<?php declare(strict_types=1);

namespace Bookstore\Models;
use PDO;

abstract class AbstractModel {
    public $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }
}