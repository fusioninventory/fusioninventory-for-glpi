<?php

namespace JsonSchema\Constraints;







abstract class Constraint implements ConstraintInterface
{
protected $checkMode = self::CHECK_MODE_NORMAL;
protected $errors = array();
protected $inlineSchemaProperty = '$schema';

const CHECK_MODE_NORMAL = 1;
const CHECK_MODE_TYPE_CAST = 2;




public function __construct($checkMode = self::CHECK_MODE_NORMAL)
{
$this->checkMode = $checkMode;
}




public function addError($path, $message)
{
$this->errors[] = array(
'property' => $path,
'message' => $message
);
}




public function addErrors(array $errors)
{
$this->errors = array_merge($this->errors, $errors);
}




public function getErrors()
{
return array_unique($this->errors, SORT_REGULAR);
}








protected function incrementPath($path, $i)
{
if ($path !== '') {
if (is_int($i)) {
$path .= '[' . $i . ']';
} else if ($i == '') {
$path .= '';
} else {
$path .= '.' . $i;
}
} else {
$path = $i;
}

return $path;
}









protected function checkArray($value, $schema = null, $path = null, $i = null)
{
$validator = new Collection($this->checkMode);
$validator->check($value, $schema, $path, $i);

$this->addErrors($validator->getErrors());
}









protected function checkObject($value, $schema = null, $path = null, $i = null)
{
$validator = new Object($this->checkMode);
$validator->check($value, $schema, $path, $i);

$this->addErrors($validator->getErrors());
}









protected function checkType($value, $schema = null, $path = null, $i = null)
{
$validator = new Type($this->checkMode);
$validator->check($value, $schema, $path, $i);

$this->addErrors($validator->getErrors());
}









protected function checkUndefined($value, $schema = null, $path = null, $i = null)
{
$validator = new Undefined($this->checkMode);
$validator->check($value, $schema, $path, $i);

$this->addErrors($validator->getErrors());
}









protected function checkString($value, $schema = null, $path = null, $i = null)
{
$validator = new String($this->checkMode);
$validator->check($value, $schema, $path, $i);

$this->addErrors($validator->getErrors());
}









protected function checkNumber($value, $schema = null, $path = null, $i = null)
{
$validator = new Number($this->checkMode);
$validator->check($value, $schema, $path, $i);

$this->addErrors($validator->getErrors());
}









protected function checkEnum($value, $schema = null, $path = null, $i = null)
{
$validator = new Enum($this->checkMode);
$validator->check($value, $schema, $path, $i);

$this->addErrors($validator->getErrors());
}




public function isValid()
{
return !$this->getErrors();
}
}