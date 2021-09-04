<?php

declare(strict_types=1);
namespace Bookstore\Domain;

interface Customer extends Payer{
    public function getMonthlyFee();
    public function getAmountToBorrow();
    public function getType();
}