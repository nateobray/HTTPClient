<?php

require_once 'vendor/autoload.php';

$response = \obray\HTTPClient::get("http://10.5.22.134:8083/");
print_r($response);
