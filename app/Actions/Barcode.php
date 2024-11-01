<?php

namespace App\Actions;

class Barcode
{
    public function __invoke():int
    {
        return rand(1234567890,50);
    }
}
