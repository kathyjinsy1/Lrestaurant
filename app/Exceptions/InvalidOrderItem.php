<?php

namespace App\Exceptions;

use Exception;

class InvalidOrderItem extends Exception
{
    public function render() {
    	return ['errors' => 'Order has invalid item.'];
    }
}
