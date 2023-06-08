<?php

require('vendor/autoload.php');

use Incubateiq\Gateway\Transaction\Base as Library;

$trans = new Library\Customer();
$response = $trans->getAllCustomers();

foreach($response as $id) {
	echo $id . "\n";
}