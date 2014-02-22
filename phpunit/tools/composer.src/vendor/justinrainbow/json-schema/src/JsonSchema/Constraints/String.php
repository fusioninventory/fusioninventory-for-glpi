<?php

namespace JsonSchema\Constraints;







class String extends Constraint
{



public function check($element, $schema = null, $path = null, $i = null)
{

 if (isset($schema->maxLength) && strlen($element) > $schema->maxLength) {
$this->addError($path, "must be at most " . $schema->maxLength . " characters long");
}


 if (isset($schema->minLength) && strlen($element) < $schema->minLength) {
$this->addError($path, "must be at least " . $schema->minLength . " characters long");
}


 if (isset($schema->pattern) && !preg_match('/' . $schema->pattern . '/', $element)) {
$this->addError($path, "does not match the regex pattern " . $schema->pattern);
}
}
}