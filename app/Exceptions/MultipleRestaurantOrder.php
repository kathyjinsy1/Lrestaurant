<?php

namespace App\Exceptions;

use Exception;

class MultipleRestaurantOrder extends Exception
{
    public function render() {
    	return ['errors' => 'You have item from other restaurant in this order.'];
    }
}
