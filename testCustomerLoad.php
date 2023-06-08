<?php

require("vendor/autoload.php");

use Incubateiq\Gateway\Transaction\Base as Library;

$id = "911828606";

$customer = new Library\Customer(null, $id);

print_r($customer);