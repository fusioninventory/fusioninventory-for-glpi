<?php











namespace Composer\Json;

use Exception;




class JsonValidationException extends Exception
{
protected $errors;

public function __construct($message, $errors = array())
{
$this->errors = $errors;
parent::__construct($message);
}

public function getErrors()
{
return $this->errors;
}
}
