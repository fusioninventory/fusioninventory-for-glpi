<?php

namespace JsonSchema\Constraints;






interface ConstraintInterface
{





function getErrors();






function addErrors(array $errors);







function addError($path, $message);






function isValid();










function check($value, $schema = null, $path = null, $i = null);
}