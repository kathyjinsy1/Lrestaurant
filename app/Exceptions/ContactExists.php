<?php

namespace App\Exceptions;

use Exception;

class ContactExists extends Exception
{
     public function render() {
    	return ['errors' => 'Double creating a contact!'];
    }
}
