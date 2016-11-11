# PHP CSV Reader / Writer

Install:
======

```bash
composer require alc/csv:dev-master

```

Csv Reader:
======

```php

use Alc\Csv\CsvReader;

$csv = new CsvReader(__DIR__.'/data.csv');

print_r($csv->readAll());

```

Csv Writer:
======

```php

use Alc\Csv\CsvWriter;

$csv = new CsvWriter(__DIR__.'/output.csv');

$csv->write(array(
	'Nom' => 'CHEMEL',
	'Prenom' => 'Alexis',
));

$csv->close();

```
