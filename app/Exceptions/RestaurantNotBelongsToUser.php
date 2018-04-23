<?php

namespace App\Exceptions;

use Exception;

class RestaurantNotBelongsToUser extends Exception
{
    public function render() {
    	return ['errors' => 'Restaurant Not Belongs To User'];
    }
}
