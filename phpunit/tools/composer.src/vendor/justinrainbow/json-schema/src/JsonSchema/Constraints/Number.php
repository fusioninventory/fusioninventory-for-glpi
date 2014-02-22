<?php

namespace JsonSchema\Constraints;







class Number extends Constraint
{



public function check($element, $schema = null, $path = null, $i = null)
{

 if (isset($schema->minimum) && $element < $schema->minimum) {
$this->addError($path, "must have a minimum value of " . $schema->minimum);
}


 if (isset($schema->maximum) && $element > $schema->maximum) {
$this->addError($path, "must have a maximum value of " . $schema->maximum);
}


 if (isset($schema->divisibleBy) && $element % $schema->divisibleBy != 0) {
$this->addError($path, "is not divisible by " . $schema->divisibleBy);
}
}
}