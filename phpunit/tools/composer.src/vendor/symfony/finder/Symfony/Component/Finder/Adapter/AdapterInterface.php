<?php










namespace Symfony\Component\Finder\Adapter;




interface AdapterInterface
{





public function setFollowLinks($followLinks);






public function setMode($mode);






public function setExclude(array $exclude);






public function setDepths(array $depths);






public function setNames(array $names);






public function setNotNames(array $notNames);






public function setContains(array $contains);






public function setNotContains(array $notContains);






public function setSizes(array $sizes);






public function setDates(array $dates);






public function setFilters(array $filters);






public function setSort($sort);






public function setPath(array $paths);






public function setNotPath(array $notPaths);






public function ignoreUnreadableDirs($ignore = true);






public function searchInDirectory($dir);






public function isSupported();






public function getName();
}
