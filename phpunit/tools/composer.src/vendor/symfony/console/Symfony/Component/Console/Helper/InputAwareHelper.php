<?php










namespace Symfony\Component\Console\Helper;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputAwareInterface;






abstract class InputAwareHelper extends Helper implements InputAwareInterface
{
protected $input;




public function setInput(InputInterface $input)
{
$this->input = $input;
}
}
