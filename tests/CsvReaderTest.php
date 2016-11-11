<?php

require __DIR__.'/../vendor/autoload.php';

use Alc\Csv\CsvReader;

$csv = new CsvReader(__DIR__.'/data.csv');

// Read line by line
while( $row = $csv->read() ) {

	print_r($row);
}

$csv->close();

// Read all
print_r($csv->readAll());