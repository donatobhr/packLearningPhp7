<?php
    declare(strict_types=1);
    
    function addNumbers(int $a, int $b, bool $printSum = false) : int {
        $sum = $a + $b;

        if($printSum) {
            echo "The sum is $sum";
        }
        return $sum;
    }


    addNumbers(1,2);
    addNumbers(1, 2, true);
?>