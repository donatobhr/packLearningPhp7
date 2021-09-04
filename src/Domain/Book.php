<?php
declare(strict_types=1);
namespace Bookstore\Domain;

class Book {
    private $id;
    private $isbn;
    private $title;
    private $author;
    private $stock;
    private $price;

    public function getId(): int {
        return (int) $this->id;
    }

    public function getIsbn(): string {
        return $this->isbn;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getAuthor(): string {
        return $this->author;
    }

    public function getStock(): int {
        return (int) $this->stock;
    }

    public function getPrice(): float {
        return (float) $this->price;
    }

    public function getCopy(): bool {
        if($this->stock < 1) {
            return false;
        }

        $this->stock--;
        return true;
    }

    public function addCopy() {
        $this->stock++;
    }
}