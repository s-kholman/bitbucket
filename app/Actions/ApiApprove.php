<?php

namespace App\Actions;

class ApiApprove
{
    public function __invoke($barcode)
    {
        $return [] = ['message' => 'order successfully aproved'];
        $return [] = ['error' => 'event cancelled'];
        $return [] = ['error' => 'no tickets'];
        $return [] = ['error' => 'no seats'];
        $return [] = ['error' => 'fan removed'];

        return $return[rand(0,1)];
    }
}
