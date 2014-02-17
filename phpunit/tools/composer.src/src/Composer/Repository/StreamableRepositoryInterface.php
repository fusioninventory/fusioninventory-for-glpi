<?php











namespace Composer\Repository;

use Composer\Package\AliasPackage;
use Composer\Package\PackageInterface;




interface StreamableRepositoryInterface extends RepositoryInterface
{




















public function getMinimalPackages();







public function loadPackage(array $data);








public function loadAliasPackage(array $data, PackageInterface $aliasOf);
}
