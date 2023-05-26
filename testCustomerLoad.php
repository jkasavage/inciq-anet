<?php

require("vendor/autoload.php");

use Incubateiq\Gateway\Transaction\Base as Library;

$id = "911828606";

$obj = new Library\Customer();
$response = $obj->load($id);

var_dump($response->debugCustomer());