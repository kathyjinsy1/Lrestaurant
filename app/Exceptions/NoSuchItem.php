<?php

namespace App\Exceptions;

use Exception;

class NoSuchItem extends Exception
{
    public function render() {
    	return ['errors' => 'no such item or option in given menu/restaurant'];
    }
}
