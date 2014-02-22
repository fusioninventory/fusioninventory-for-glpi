<?php

namespace JsonSchema\Constraints;







class Undefined extends Constraint
{



function check($value, $schema = null, $path = null, $i = null)
{
if (!is_object($schema)) {
return;
}

$path = $this->incrementPath($path, $i);


 $this->validateCommonProperties($value, $schema, $path);


 $this->validateTypes($value, $schema, $path, $i);


}









public function validateTypes($value, $schema = null, $path = null, $i = null)
{

 if (is_array($value)) {
$this->checkArray($value, $schema, $path, $i);
}


 if (is_object($value) && isset($schema->properties)) {
$this->checkObject($value, $schema->properties, $path, isset($schema->additionalProperties) ? $schema->additionalProperties : null);
}


 if (is_string($value)) {
$this->checkString($value, $schema, $path, $i);
}


 if (is_numeric($value)) {
$this->checkNumber($value, $schema, $path, $i);
}


 if (isset($schema->enum)) {
$this->checkEnum($value, $schema, $path, $i);
}
}









protected function validateCommonProperties($value, $schema = null, $path = null, $i = null)
{

 if (isset($schema->extends)) {
$this->checkUndefined($value, $schema->extends, $path, $i);
}


 if (is_object($value) && $value instanceOf Undefined) {
if (isset($schema->required) && $schema->required) {
$this->addError($path, "is missing and it is required");
}
} else {
$this->checkType($value, $schema, $path);
}


 if (isset($schema->disallow)) {
$initErrors = $this->getErrors();

$this->checkUndefined($value, $schema->disallow, $path);


 if (count($this->getErrors()) == count($initErrors)) {
$this->addError($path, " disallowed value was matched");
} else {
$this->errors = $initErrors;
}
}
}
}