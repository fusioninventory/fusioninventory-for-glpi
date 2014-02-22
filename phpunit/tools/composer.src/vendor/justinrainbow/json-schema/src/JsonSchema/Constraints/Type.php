<?php

namespace JsonSchema\Constraints;







class Type extends Constraint
{



function check($value = null, $schema = null, $path = null, $i = null)
{
$type = isset($schema->type) ? $schema->type : null;
$isValid = true;

if (is_array($type)) {

 $validatedOneType = false;
$errors = array();
foreach ($type as $tp) {
$validator = new Type($this->checkMode);
$subSchema = new \stdClass();
$subSchema->type = $tp;
$validator->check($value, $subSchema, $path, null);
$error = $validator->getErrors();

if (!count($error)) {
$validatedOneType = true;
break;
} else {
$errors = $error;
}
}
if (!$validatedOneType) {
return $this->addErrors($errors);
}
} elseif (is_object($type)) {
$this->checkUndefined($value, $type, $path);
} else {
$isValid = $this->validateType($value, $type);
}

if ($isValid === false) {
$this->addError($path, gettype($value) . " value found, but a " . $type . " is required");
}
}









protected function validateType($value, $type)
{

 if (!$type) {
return true;
}

switch ($type) {
case 'integer' :
return (integer)$value == $value ? true : is_int($value);
case 'number' :
return is_numeric($value);
case 'boolean' :
return is_bool($value);
case 'object' :
return is_object($value);

 case 'array' :
return is_array($value);
case 'string' :
return is_string($value);
case 'null' :
return is_null($value);
case 'any' :
return true;
default:
throw new \InvalidArgumentException((is_object($value) ? 'object' : $value) . ' is a invalid type for ' . $type);
}
}
}