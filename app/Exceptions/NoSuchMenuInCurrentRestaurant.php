<?php

namespace App\Exceptions;

use Exception;

class NoSuchMenuInCurrentRestaurant extends Exception
{
    public function render() {
    	return ['errors' => 'The current menu does not belong to current restaurant'];
    }
}
