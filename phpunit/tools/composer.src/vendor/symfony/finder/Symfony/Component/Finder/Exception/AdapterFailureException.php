<?php










namespace Symfony\Component\Finder\Exception;

use Symfony\Component\Finder\Adapter\AdapterInterface;






class AdapterFailureException extends \RuntimeException implements ExceptionInterface
{



private $adapter;






public function __construct(AdapterInterface $adapter, $message = null, \Exception $previous = null)
{
$this->adapter = $adapter;
parent::__construct($message ?: 'Search failed with "'.$adapter->getName().'" adapter.', $previous);
}




public function getAdapter()
{
return $this->adapter;
}
}
