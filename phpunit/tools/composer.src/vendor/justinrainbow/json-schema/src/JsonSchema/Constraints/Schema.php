<?php

namespace JsonSchema\Constraints;







class Schema extends Constraint
{



public function check($element, $schema = null, $path = null, $i = null)
{
if ($schema !== null) {

 $this->checkUndefined($element, $schema, '', '');
} elseif (isset($element->{$this->inlineSchemaProperty})) {

 $this->checkUndefined($element, $element->{$this->inlineSchemaProperty}, '', '');
} else {
throw new \InvalidArgumentException('no schema found to verify against');
}
}
}