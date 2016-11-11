<?php

require __DIR__.'/../vendor/autoload.php';

use Alc\Csv\CsvWriter;

$csv = new CsvWriter();
// $csv = new CsvWriter(__DIR__.'/output.csv');

$csv->write(array(
	'Nom' => 'CHEMEL',
	'Prenom' => 'Alexis',
));

$csv->write(array(
	'Nom' => 'CHEMEL',
	'Prenom' => 'Ale"xis',
));

$csv->write(array(
	'Nom' => 'CHEMEL',
	'Prenom' => 'Ale;xis',
));

$csv->close();
