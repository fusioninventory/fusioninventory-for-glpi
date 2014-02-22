<?php

namespace JsonSchema\Constraints;







class Object extends Constraint
{



function check($element, $definition = null, $path = null, $additionalProp = null)
{

 $this->validateDefinition($element, $definition, $path);


 $this->validateElement($element, $definition, $path, $additionalProp);
}









public function validateElement($element, $objectDefinition = null, $path = null, $additionalProp = null)
{
foreach ($element as $i => $value) {

$property = $this->getProperty($element, $i, new Undefined());
$definition = $this->getProperty($objectDefinition, $i);


 if ($this->getProperty($definition, 'required') && !$property) {
$this->addError($path, "the property " . $i . " is required");
}


 if ($additionalProp === false && $this->inlineSchemaProperty !== $i && !$definition) {
$this->addError($path, "The property " . $i . " is not defined and the definition does not allow additional properties");
}


 if ($additionalProp && !$definition) {
$this->checkUndefined($value, $additionalProp, $path, $i);
}


 $require = $this->getProperty($definition, 'requires');
if ($require && !$this->getProperty($element, $require)) {
$this->addError($path, "the presence of the property " . $i . " requires that " . $require . " also be present");
}


 $this->checkUndefined($value, $definition ? : new \stdClass(), $path, $i);
}
}








public function validateDefinition($element, $objectDefinition = null, $path = null)
{
foreach ($objectDefinition as $i => $value) {
$property = $this->getProperty($element, $i, new Undefined());
$definition = $this->getProperty($objectDefinition, $i);
$this->checkUndefined($property, $definition, $path, $i);
}
}









protected function getProperty($element, $property, $fallback = null)
{
if (is_array($element) ) {
return array_key_exists($property, $element) ? $element[$property] : $fallback;
} else {
return isset($element->$property) ? $element->$property : $fallback;
}
}
}