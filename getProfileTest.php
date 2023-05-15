<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\CustomerProfile as Customer;

$obj = new Customer();
$profile = $obj->getProfile('911759913');

print_r($profile);