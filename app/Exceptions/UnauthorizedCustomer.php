<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedCustomer extends Exception
{
    
     public function render() {
    	return ['errors' => 'The customer is not authorized'];
    }
}
