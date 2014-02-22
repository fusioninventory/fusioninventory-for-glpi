<?php

namespace JsonSchema\Constraints;







class Collection extends Constraint
{



public function check($value, $schema = null, $path = null, $i = null)
{

 if (isset($schema->minItems) && count($value) < $schema->minItems) {
$this->addError($path, "There must be a minimum of " . $schema->minItems . " in the array");
}

 if (isset($schema->maxItems) && count($value) > $schema->maxItems) {
$this->addError($path, "There must be a maximum of " . $schema->maxItems . " in the array");
}

 
 if (isset($schema->uniqueItems) && array_unique($value) != $value) {
$this->addError($path, "There are no duplicates allowed in the array");
}


 if (isset($schema->items)) {
$this->validateItems($value, $schema, $path, $i);
}
}









protected function validateItems($value, $schema = null, $path = null, $i = null)
{
if (!is_array($schema->items)) {

 foreach ($value as $k => $v) {
$initErrors = $this->getErrors();


 if (!isset($schema->additionalItems) || $schema->additionalItems === false) {
$this->checkUndefined($v, $schema->items, $path, $k);
}


 if (count($initErrors) < count($this->getErrors()) && (isset($schema->additionalItems) && $schema->additionalItems !== false)) {
$secondErrors = $this->getErrors();
$this->checkUndefined($v, $schema->additionalItems, $path, $k);
}


 if (isset($secondErrors) && count($secondErrors) < $this->getErrors()) {
$this->errors = $secondErrors;
} elseif (isset($secondErrors) && count($secondErrors) == count($this->getErrors())) {
$this->errors = $initErrors;
}
}
} else {

 foreach ($value as $k => $v) {
if (array_key_exists($k, $schema->items)) {
$this->checkUndefined($v, $schema->items[$k], $path, $k);
} else {

 if (array_key_exists('additionalItems', $schema) && $schema->additionalItems !== false) {
$this->checkUndefined($v, $schema->additionalItems, $path, $k);
} else {
$this->addError(
$path,
'The item ' . $i . '[' . $k . '] is not defined in the objTypeDef and the objTypeDef does not allow additional properties'
);
}
}
}


 for ($k = count($value); $k < count($schema->items); $k++) {
$this->checkUndefined(new Undefined(), $schema->items[$k], $path, $k);
}
}
}
}