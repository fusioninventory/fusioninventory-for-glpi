<?php










namespace Symfony\Component\Finder\Expression;




interface ValueInterface
{





public function render();






public function renderPattern();






public function isCaseSensitive();






public function getType();






public function prepend($expr);






public function append($expr);
}
