<?php

require __DIR__ . '/../../../vendor/autoload.php';


error_reporting(1);

$openapi = \OpenApi\Generator::scan(['../../../routes', './'], ['pattern' => '*.php']);
// $openapi = \OpenApi\Util::finder(['../../../rest/routes', './'], NULL, '*.php');
// $openapi = \OpenApi\scan(['../../../rest', './'], ['pattern' => '*.php']);

header('Content-Type: application/json');
echo $openapi->toJson();
