<?php

namespace JsonSchema;

use JsonSchema\Constraints\Schema;
use JsonSchema\Constraints\Constraint;








class Validator extends Constraint
{







function check($value, $schema = null, $path = null, $i = null)
{
$validator = new Schema($this->checkMode);
$validator->check($value, $schema);
$this->addErrors($validator->getErrors());
}
}