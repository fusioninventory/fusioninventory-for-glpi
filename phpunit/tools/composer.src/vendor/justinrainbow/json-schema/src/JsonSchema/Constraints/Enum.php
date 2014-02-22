<?php

namespace JsonSchema\Constraints;







class Enum extends Constraint
{



public function check($element, $schema = null, $path = null, $i = null)
{
foreach ($schema->enum as $possibleValue) {
if ($possibleValue == $element) {
$found = true;
break;
}
}

if (!isset($found)) {
$this->addError($path, "does not have a value in the enumeration " . implode(', ', $schema->enum));
}
}
}