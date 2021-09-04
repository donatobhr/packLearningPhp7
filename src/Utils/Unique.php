<?php

namespace Bookstore\Utils;

use Bookstore\Exceptions\ExceededMaxAllowedException;
use Bookstore\Exceptions\InvalidIdException;

trait Unique {
    protected $id;

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId(): int {
        return (int) $this->id;
    }
}