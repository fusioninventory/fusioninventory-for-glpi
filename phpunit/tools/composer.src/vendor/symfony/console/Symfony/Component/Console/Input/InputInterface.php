<?php










namespace Symfony\Component\Console\Input;






interface InputInterface
{





public function getFirstArgument();











public function hasParameterOption($values);












public function getParameterOption($values, $default = false);






public function bind(InputDefinition $definition);








public function validate();






public function getArguments();








public function getArgument($name);









public function setArgument($name, $value);








public function hasArgument($name);






public function getOptions();








public function getOption($name);









public function setOption($name, $value);








public function hasOption($name);






public function isInteractive();






public function setInteractive($interactive);
}
