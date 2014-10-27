# Introduction

This library provides a simple interface to the Google Spreadsheet API. It's a fork of https://github.com/asimlqt/php-google-spreadsheet-client changed to be compatible with PHP 5.2.1+.

There are a couple of important things to note.

* This library requires a valid OAuth access token to work but does not provide any means of generating one. The [Google APIs Client Library for PHP](https://code.google.com/p/google-api-php-client/) has all the functionality required for for generating and refreshing tokens so it would have been a waste of time duplicating the official google library.
* You can not create spreadsheets using this (PHP Google Spreadsheet Client) library, as creating spreadsheets is not part of the Spreadsheet API and the functionality already exists in the official Google Client Library.

I strongly recommend you read through the [official Google Spreadsheet API documentation](https://developers.google.com/google-apps/spreadsheets) to get a grasp of the concepts.

# Usage

## Installation

Using [composer](https://getcomposer.org/) is the recommended way to install it.

1 - Add "alepane21/php-google-spreadsheet-client" as a dependency in your project's composer.json file.

```json
{
    "require": {
        "alepane21/php-google-spreadsheet-client": "2.2.*"
    }
}
```

2 - Download and install Composer.

```
curl -sS https://getcomposer.org/installer | php
```

3 - Install your dependencies.

```
php composer.phar install
```

4 - Require Composer's autoloader.

```
require 'vendor/autoload.php';
```


## Bootstrapping

The first thing you will need to do is initialize the service request factory:

```php
$serviceRequest = new Google_Spreadsheet_DefaultServiceRequest($accessToken);
Google_Spreadsheet_ServiceRequestFactory::setInstance($serviceRequest);
```

## Retrieving a list of spreadsheets

```php
$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
```

SpreadsheetFeed implements ArrayIterator so you can iterate over it using a foreach loop or you can retrieve a single spreadsheet by name.

```php
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
```

## Retrieving a list of worksheets

You can retrieve a list of worksheets from a spreadsheet by calling the getWorksheets() method.

```php
$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
$worksheetFeed = $spreadsheet->getWorksheets();
```

You can loop over each worksheet or get a single worksheet by title.

```php
$worksheet = $worksheetFeed->getByTitle('Sheet 1');
```

## Adding a worksheet

```php
$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
$spreadsheet->addWorksheet('New Worksheet', 50, 20);
```

The only required parameter is the worksheet name, The row and column count are optional. The default value for rows is 100 and columns is 10.

## Deleting a worksheet

```php
$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
$worksheetFeed = $spreadsheet->getWorksheets();
$worksheet = $worksheetFeed->getByTitle('New Worksheet');
$worksheet->delete();
```

## Working with list-based feeds

### Retrieving a list-based feed

```php
$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
$worksheetFeed = $spreadsheet->getWorksheets();
$worksheet = $worksheetFeed->getByTitle('Sheet 1');
$listFeed = $worksheet->getListFeed();
```

Once you have a list feed you can loop over each entry.

```php
foreach ($listFeed->getEntries() as $entry) {
	$values = $entry->getValues();
}
```

The getValues() method returns an associative array where the keys are the column names and the values are the cell content.

### Adding a list row

```php
$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
$worksheetFeed = $spreadsheet->getWorksheets();
$worksheet = $worksheetFeed->getByTitle('Sheet 1');
$listFeed = $worksheet->getListFeed();

$row = array('name'=>'John', 'age'=>25);
$listFeed->insert($row);
```

### Updating a list row

```php
$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
$worksheetFeed = $spreadsheet->getWorksheets();
$worksheet = $worksheetFeed->getByTitle('Sheet 1');
$listFeed = $worksheet->getListFeed();
$entries = $listFeed->getEntries();
$listEntry = $entries[0];

$values = $listEntry->getValues();
$values['name'] = 'Joe';
$listEntry->update($values);
```

### Adding headers to a new worksheet

The Google Spreadsheet API does not allow you to update a list row if headers are not already assigned. So, when you create a new worksheet, before you can add data to a worksheet using the 'Adding/Updating a list row' methods above, you need to add headers.

To add headers to a worksheet, use the following:
```php

$spreadsheetService = new Google_Spreadsheet_SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$spreadsheet = $spreadsheetFeed->getByTitle('MySpreadsheet');
$worksheetFeed = $spreadsheet->getWorksheets();
$worksheet = $worksheetFeed->getByTitle('Sheet 1');
$cellFeed = $worksheet->getCellFeed();

$cellFeed->editCell(1,1, "Row1Col1Header");
$cellFeed->editCell(1,2, "Row1Col2Header");
$cellFeed->editCell(1,3, "Row1Col3Header");
$cellFeed->editCell(1,4, "Row1Col4Header");

```
