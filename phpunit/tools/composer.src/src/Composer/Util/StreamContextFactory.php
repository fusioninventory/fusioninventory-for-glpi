<?php











namespace Composer\Util;







final class StreamContextFactory
{









public static function getContext($url, array $defaultOptions = array(), array $defaultParams = array())
{
$options = array('http' => array(

 'follow_location' => 1,
'max_redirects' => 20,
));


 if (!empty($_SERVER['HTTP_PROXY']) || !empty($_SERVER['http_proxy'])) {

 $proxy = parse_url(!empty($_SERVER['http_proxy']) ? $_SERVER['http_proxy'] : $_SERVER['HTTP_PROXY']);
}

if (!empty($proxy)) {
$proxyURL = isset($proxy['scheme']) ? $proxy['scheme'] . '://' : '';
$proxyURL .= isset($proxy['host']) ? $proxy['host'] : '';

if (isset($proxy['port'])) {
$proxyURL .= ":" . $proxy['port'];
} elseif ('http://' == substr($proxyURL, 0, 7)) {
$proxyURL .= ":80";
} elseif ('https://' == substr($proxyURL, 0, 8)) {
$proxyURL .= ":443";
}


 $proxyURL = str_replace(array('http://', 'https://'), array('tcp://', 'ssl://'), $proxyURL);

if (0 === strpos($proxyURL, 'ssl:') && !extension_loaded('openssl')) {
throw new \RuntimeException('You must enable the openssl extension to use a proxy over https');
}

$options['http']['proxy'] = $proxyURL;


 if (!empty($_SERVER['no_proxy']) && parse_url($url, PHP_URL_HOST)) {
$pattern = new NoProxyPattern($_SERVER['no_proxy']);
if ($pattern->test($url)) {
unset($options['http']['proxy']);
}
}


 if (!empty($options['http']['proxy'])) {

 switch (parse_url($url, PHP_URL_SCHEME)) {
case 'http': 
 $reqFullUriEnv = getenv('HTTP_PROXY_REQUEST_FULLURI');
if ($reqFullUriEnv === false || $reqFullUriEnv === '' || (strtolower($reqFullUriEnv) !== 'false' && (bool) $reqFullUriEnv)) {
$options['http']['request_fulluri'] = true;
}
break;
case 'https': 
 $reqFullUriEnv = getenv('HTTPS_PROXY_REQUEST_FULLURI');
if ($reqFullUriEnv === false || $reqFullUriEnv === '' || (strtolower($reqFullUriEnv) !== 'false' && (bool) $reqFullUriEnv)) {
$options['http']['request_fulluri'] = true;
}
break;
}

if (isset($proxy['user'])) {
$auth = urldecode($proxy['user']);
if (isset($proxy['pass'])) {
$auth .= ':' . urldecode($proxy['pass']);
}
$auth = base64_encode($auth);


 if (isset($defaultOptions['http']['header'])) {
if (is_string($defaultOptions['http']['header'])) {
$defaultOptions['http']['header'] = array($defaultOptions['http']['header']);
}
$defaultOptions['http']['header'][] = "Proxy-Authorization: Basic {$auth}";
} else {
$options['http']['header'] = array("Proxy-Authorization: Basic {$auth}");
}
}
}
}

$options = array_replace_recursive($options, $defaultOptions);

if (isset($options['http']['header'])) {
$options['http']['header'] = self::fixHttpHeaderField($options['http']['header']);
}

return stream_context_create($options, $defaultParams);
}











private static function fixHttpHeaderField($header)
{
if (!is_array($header)) {
$header = explode("\r\n", $header);
}
uasort($header, function ($el) {
return preg_match('{^content-type}i', $el) ? 1 : -1;
});

return $header;
}
}
