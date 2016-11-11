# csv
PHP CSV Reader / Writer

Install:
======

```bash
composer require alc/curl:dev-master

```

Csv Reader:
======

```php

use Alc\Csv\CsvReader;

$csv = new CsvReader(__DIR__.'/data.csv');

print_r($csv->readAll());

```

