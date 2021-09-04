<?php

namespace Bookstore\Exceptions;
use Exception;

class ExceededMaxAllowedException extends Exception {
    public function __construct($message){
        $message = $message ?? "Exceeded max allowed.";
        parent::__construct($message);
    }
}