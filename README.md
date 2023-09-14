<img src="https://banners.beyondco.de/Laravel%20DocuWare.png?theme=light&packageManager=composer+require&packageName=codebar-ag%2Flaravel-docuware&pattern=circuitBoard&style=style_1&description=An+opinionated+way+to+integrate+DocuWare+with+Laravel&md=1&showWatermark=0&fontSize=175px&images=document-report">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codebar-ag/laravel-docuware.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-docuware)
[![GitHub-Tests](https://github.com/codebar-ag/laravel-docuware/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-docuware/actions/workflows/run-tests.yml)
[![GitHub Code Style](https://github.com/codebar-ag/laravel-docuware/actions/workflows/fix-php-code-style-issues.yml/badge.svg?branch=main)](https://github.com/codebar-ag/laravel-docuware/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/codebar-ag/laravel-docuware.svg?style=flat-square)](https://packagist.org/packages/codebar-ag/laravel-docuware)

This package was developed to give you a quick start to communicate with the
DocuWare REST API. It is used to query the most common endpoints.

⚠️ This package is not designed as a replacement of the official
[DocuWare REST API](https://developer.docuware.com/rest/index.html).
See the documentation if you need further functionality. ⚠️

## 💡 What is DocuWare?

DocuWare provides cloud document management and workflow automation software
that enables you to digitize, secure and work with business documents,
then optimize the processes that power the core of your business.

## 🛠 Requirements

### > = v3.0

- PHP: `^8.2``
- Laravel: `^10.*`
- DocuWare Cloud Access

### > = v2.0

- PHP: `^8.1` |`^8.2`
- Laravel: `^9.*` | `^10.*`
- DocuWare Cloud Access
-

### > = v1.2

- PHP: `^8.1`
- Laravel: `^9.*`
- DocuWare Cloud Access

### < v1.2

- PHP: `^8.0`
- Laravel: `^8.*`
- DocuWare Cloud Access

## ⚙️ Installation

You can install the package via composer:

```bash
composer require codebar-ag/laravel-docuware
```

Add the following environment variables to your `.env` file:
The "DOCUWARE_COOKIES" variable is optional and only used if you want to set the request cookie manually.

```bash
DOCUWARE_URL=https://domain.docuware.cloud
DOCUWARE_USERNAME=user@domain.test
DOCUWARE_PASSWORD=password
DOCUWARE_PASSPHRASE="passphrase"
DOCUWARE_COOKIES="cookie"
```

With the passphrase we are able to encrypt the URLs.

⚠️ You need to escape backslashes in your passphrase with another backslash:

```bash 
# ❌ Passphrase contains a backslash and is not escaped:
DOCUWARE_PASSPHRASE="a#bcd>2~C1'abc\#"

# ✅ We need to escape the backslash with another backslash:
DOCUWARE_PASSPHRASE="a#bcd>2~C1'abc\\#"
```

## 🏗 Usage

```php
use CodebarAg\DocuWare\Facades\DocuWare;

/**
 * Return an organization.
 */
$organization = DocuWare::getOrganization(string $organizationId): CodebarAg\DocuWare\DTO\Organization;

/**
 * Return all organizations.
 */
$organizations = DocuWare::getOrganizations(): Illuminate\Support\Collection;

/**
 * Return all file cabinets.
 */
$cabinets = DocuWare::getFileCabinets(): Illuminate\Support\Collection|CodebarAg\DocuWare\DTO\FileCabinet[];

/**
 * Return all fields of a file cabinet.
 */
$fields = DocuWare::getFields(string $fileCabinetId): Illuminate\Support\Collection|CodebarAg\DocuWare\DTO\Field[];

/**
 * Return all dialogs of a file cabinet.
 */
$dialogs = DocuWare::getDialogs(string $fileCabinetId): Illuminate\Support\Collection|CodebarAg\DocuWare\DTO\Dialog[];

/**
 * Return all used values for a specific field.
 */
$values = DocuWare::getSelectList(string $fileCabinetId, string $dialogId, string $fieldName): array;

/**
 * Return a document.
 */
$document = DocuWare::getDocument(string $fileCabinetId, int $documentId): CodebarAg\DocuWare\DTO\Document;

/**
 * Return image preview of a document.
 */
$content = DocuWare::getDocumentPreview(string $fileCabinetId, int $documentId): string;

/**
 * Download single document.
 */
$content = DocuWare::downloadDocument(string $fileCabinetId, int $documentId): string;

/**
 * Download multiple documents.
 * 
 * Although there are no mentioned limits in the documentation,
 * it is not advisable to download more than 100 documents at once.
 * 
 * Also note there is a default request timeout of 30 seconds.
 */
$content = DocuWare::downloadDocuments(string $fileCabinetId, array $documentIds): string;

/**
 * Download a document thumbnail.
 */
$thumbnail = DocuWare::downloadDocumentThumbnail(string $fileCabinetId, int $documentId, int $section, int $page = 0): CodebarAg\DocuWare\DTO\DocumentThumbnail;

/**
 * Update value of a indexed field.
 */
$value = DocuWare::updateDocumentValue(string $fileCabinetId, int $documentId, string $fieldName, string $newValue, bool $forceUpdate = false): null|int|float|Carbon|string;

/**
 * Update multiple values of indexed fields.
 */
$value = DocuWare::updateDocumentValues(string $fileCabinetId, int $documentId, array $values, bool $forceUpdate = false): null|int|float|Carbon|string;

/**
 * Upload new document.
 */
$document = DocuWare::uploadDocument(string $fileCabinetId, string $fileContent, string $fileName, ?Collection $indexes = null): CodebarAg\DocuWare\DTO\Document;

/**
 * Get total document count.
 */
$content = DocuWare::documentCount(string $fileCabinetId, string $dialogId): int;

/**
 * Upload new document with index values.
 */
use CodebarAg\DocuWare\DTO\DocumentIndex;
 
$indexes = collect([
    DocumentIndex::make('DOCUMENT_TEXT', 'Indexed Text'),
    DocumentIndex::make('DOCUMENT_NUMBER', 42),
]);

$document = DocuWare::uploadDocument(
    $fileCabinetId,
    $fileContent,
    $fileName,
    $indexes,
): CodebarAg\DocuWare\DTO\Document;

/**
 * Delete document.
 */
DocuWare::deleteDocument($fileCabinetId, $documentId): void;
```

## 🔍 Search usage

```php
use CodebarAg\DocuWare\Facades\DocuWare;

/**
 * Most basic example to search for documents. You only need to provide a valid
 * file cabinet id.
 */
$id = '87356f8d-e50c-450b-909c-4eaccd318fbf';

$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->get();

/**
 * Search in multiple file cabinets. Provide an array of file cabinet ids.
 */
$fileCabinetIds = [
    '0ee72de3-4258-4353-8020-6a3ff6dd650f',
    '3f9cb4ff-82f2-44dc-b439-dd648269064f',
];

$paginator = DocuWare::search()
    ->fileCabinets($fileCabinetIds)
    ->get();

/**
 * Find results on the next page. 
 * 
 * Default: 1
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->page(2)
    ->get();
    
/**
 * Define the number of results which should be shown per page.
 * 
 * Default: 50
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->perPage(30)
    ->get();

/**
 * Use the full-text search. You have to activate full-text search in your file
 * cabinet before you can use this feature.
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->fulltext('My secret document')
    ->get();

/**
 * Search documents which are created from the first of march.
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->filterDate('DWSTOREDATETIME', '>=', Carbon::create(2021, 3, 1))
    ->get();

/**
 * Search documents which are created until the first of april.
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->filterDate('DWSTOREDATETIME', '<', Carbon::create(2021, 4, 1))
    ->get();

/**
 * Order the results by field name. Supported values: 'asc', 'desc'
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->orderBy('DWSTOREDATETIME', 'desc')
    ->get();

/**
 * Search documents filtered to the value. You can specify multiple filters.
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->filter('TYPE', 'Order')
    ->filter('OTHER_FIELD', 'other')
    ->get();
    
/**
 * You can specify the dialog which should be used.
 */
$dialogId = 'bb42c30a-89fc-4b81-9091-d7e326caba62';

$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->dialog($dialogId)
    ->get();
    
/**
 * You can also combine everything.
 */
$paginator = DocuWare::search()
    ->fileCabinet($id)
    ->page(2)
    ->perPage(30)
    ->fulltext('My secret document')
    ->filterDate('DWSTOREDATETIME', '>=', Carbon::create(2021, 3, 1))
    ->filterDate('DWSTOREDATETIME','<',Carbon::create(2021, 4, 1))
    ->filter('TYPE', 'Order')
    ->filter('OTHER_FIELD', 'other')
    ->orderBy('DWSTOREDATETIME', 'desc')
    ->dialog($dialogId)
    ->get();
```

## 🖼 Make encrypted URL

```php
use CodebarAg\DocuWare\Facades\DocuWare;

/**
 * Make encrypted URL for a document in a file cabinet.
 */
$fileCabinetId = '87356f8d-e50c-450b-909c-4eaccd318fbf';
$documentId = 42;

$url = DocuWare::url()
    ->fileCabinet($fileCabinetId)
    ->document($documentId)
    ->make();

/**
 * Make encrypted URL for a document in a basket.
 */
$basketId = 'b_87356f8d-e50c-450b-909c-4eaccd318fbf';

$url = DocuWare::url()
    ->basket($basketId)
    ->document($documentId)
    ->make();

/**
 * Make encrypted URL valid for a specific amount of time. In the example below
 * the URL is valid for one week. Afterwards the URL is no longer working.
 */
$url = DocuWare::url()
    ->fileCabinet($fileCabinetId)
    ->document($documentId)
    ->validUntil(now()->addWeek())
    ->make();
```

Please see [Tests](tests/Feature/DocuWare.php) for more details.

## 🏋️ DTO showcase

```php
CodebarAg\DocuWare\DTO\OrganizationIndex {
  +id: "2f071481-095d-4363-abd9-29ef845a8b05"              // string
  +name: "Fake File Cabinet"                               // string
  +guid: "1334c006-f095-4ae7-892b-fe59282c8bed"            // string|null
}
```

```php
CodebarAg\DocuWare\DTO\Organization {
  +id: "2f071481-095d-4363-abd9-29ef845a8b05"              // string
  +name: "Fake File Cabinet"                               // string
  +guid: "1334c006-f095-4ae7-892b-fe59282c8bed"            // string|null
  +additionalInfo: []                                      // array
  +configurationRights: []                                 // array
}
```

```php
CodebarAg\DocuWare\DTO\FileCabinet {
  +id: "2f071481-095d-4363-abd9-29ef845a8b05"              // string
  +name: "Fake File Cabinet"                               // string
  +color: "Yellow"                                         // string
  +isBasket: true                                          // bool
  +assignedCabinet: "889c13cc-c636-4759-a704-1e6500d2d70f" // string
}
```

```php
CodebarAg\DocuWare\DTO\Dialog {
  +id: "fae3b667-53e9-48dd-9004-34647a26112e"            // string
  +type: "ResultList"                                    // string
  +label: "Fake Dialog"                                  // string
  +isDefault: true                                       // boolean
  +fileCabinetId: "1334c006-f095-4ae7-892b-fe59282c8bed" // string
}
```

```php
CodebarAg\DocuWare\DTO\Field {
  +name: "FAKE_FIELD"  // string
  +label: "Fake Field" // string
  +type: "Memo"        // string
  +scope: "User"       // string
```

```php
CodebarAg\DocuWare\DTO\Field {
  +name: "FAKE_FIELD"  // string
  +label: "Fake Field" // string
  +type: "Memo"        // string
  +scope: "User"       // string
```

```php
CodebarAg\DocuWare\DTO\Document {
  +id: 659732                                              // integer
  +file_size: 765336                                       // integer
  +total_pages: 100                                        // integer
  +title: "Fake Title"                                     // string
  +extension: ".pdf"                                       // string
  +content_type: "application/pdf"                         // string
  +file_cabinet_id: "a233b03d-dc63-42dd-b774-25b3ff77548f" // string
  +created_at: Illuminate\Support\Carbon                   // Carbon
  +updated_at: Illuminate\Support\Carbon                   // Carbon
  +fields: Illuminate\Support\Collection {                 // Collection|DocumentField[]
    #items: array:2 [
      0 => CodebarAg\DocuWare\DTO\DocumentField            // DocumentField
      1 => CodebarAg\DocuWare\DTO\DocumentField            // DocumentField
    ]
  }
}
```

```php
CodebarAg\DocuWare\DTO\DocumentThumbnail {
  +mime: "image/png"                                        // string
  +data: "somedata"                                         // string
  +base64: "data:image/png;base64,WXpJNWRGcFhVbWhrUjBVOQ==" // string
}
```

```php
CodebarAg\DocuWare\DTO\TableRow {
   +fields: Illuminate\Support\Collection {                 // Collection|DocumentField[]
    #items: array:2 [
      0 => CodebarAg\DocuWare\DTO\DocumentField            // DocumentField
      1 => CodebarAg\DocuWare\DTO\DocumentField            // DocumentField
    ]
}
```

```php
CodebarAg\DocuWare\DTO\DocumentPaginator
  +total: 39                                  // integer
  +per_page: 10                               // integer
  +current_page: 9                            // integer
  +last_page: 15                              // integer
  +from: 1                                    // integer
  +to: 10                                     // integer
  +documents: Illuminate\Support\Collection { // Collection|Document[]
    #items: array:2 [
      0 => CodebarAg\DocuWare\DTO\Document    // Document
      1 => CodebarAg\DocuWare\DTO\Document    // Document
    ]
  }
  +error: CodebarAg\DocuWare\DTO\ErrorBag {   // ErrorBag|null
    +code: 422                                // int
    +message: "'000' is not valid cabinet id" // string
  }
}
```

## 🔐 Authentication

You only need to provide correct credentials. Everything else is automatically
handled from the package. Under the hood we are storing the authentication
cookie in the cache named *docuware.cookies*.

You can run `php artisan docuware:list-auth-cookie` command to get your auth session that you can use in your `.env`
file `DOCUWARE_COOKIES` key.

But if you need further control you can use the following methods to login and
logout with DocuWare:

```php
use CodebarAg\DocuWare\Facades\DocuWare;

/**
 * Login with your credentials. You only need to login once. Afterwards the
 * authentication cookie is stored in the cache as `docuware.cookies` and
 * is used for all further requests.
 */
DocuWare::login();

/**
 * Logout your current session. Removes the authentication cookie in the cache.
 */
DocuWare::logout();
```

### Manual authentication

If you want to provide your own authentication cookie you can use the following connector
to authenticate with the DocuWare REST API:

```php
use CodebarAg\DocuWare\Connectors\StaticCookieConnector;
```




## 💥 Exceptions explained

- `CodebarAg\DocuWare\Exceptions\UnableToMakeRequest`

This is thrown if you are not authorized to make the request.

---

- `CodebarAg\DocuWare\Exceptions\UnableToProcessRequest`

This is thrown if you passed wrong attributes. For example a file cabinet ID
which does not exist.

---

- `CodebarAg\DocuWare\Exceptions\UnableToLogin`

This exception can only be thrown during the login if the credentials did not
match.

---

- `CodebarAg\DocuWare\Exceptions\UnableToLoginNoCookies`

This exception can only be thrown during the login if there was no cookies in
the response from the api.

---

- `CodebarAg\DocuWare\Exceptions\UnableToFindPassphrase`

This exception can only be thrown during the url making if the passphrase
could not be found.

---

- `CodebarAg\DocuWare\Exceptions\UnableToMakeUrl`

Something is wrong during the URL making.

---

- `CodebarAg\DocuWare\Exceptions\UnableToUpdateFields`

No fields were supplied.

---

- `CodebarAg\DocuWare\Exceptions\UnableToGetDocumentCount`

Something is wrong with the response from getting the document count.

---

- `Illuminate\Http\Client\RequestException`

All other cases if the response is not successfully.

## ✨ Events

Following events will be fired:

```php 
use CodebarAg\DocuWare\Events\DocuWareResponseLog;

// Log each response from the DocuWare REST API.
DocuWareResponseLog::class => [
    //
],
```

## 🔧 Configuration file

You can publish the config file with:

```bash
php artisan vendor:publish --provider="CodebarAg\DocuWare\DocuWareServiceProvider" --tag="docuware-config"
```

This is the contents of the published config file:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Connection
    |--------------------------------------------------------------------------
    | Select a connector to authenticate with. You can choose between: WITHOUT_COOKIE, STATIC_COOKIE
    |
    */

    'connection' => ConnectionEnum::WITHOUT_COOKIE,

    /*
    |--------------------------------------------------------------------------
    | Cache driver
    |--------------------------------------------------------------------------
    | You may like to define a different cache driver than the default Laravel cache driver.
    |
    */

    'cache_driver' => env('DOCUWARE_CACHE_DRIVER', env('CACHE_DRIVER', 'file')),

    /*
    |--------------------------------------------------------------------------
    | Cookies
    |--------------------------------------------------------------------------
    | This variable is optional and only used if you want to set the request cookie manually.
    |
    */

    'cookies' => env('DOCUWARE_COOKIES'),

    /*
    |--------------------------------------------------------------------------
    | Requests timeout
    |--------------------------------------------------------------------------
    | This variable is optional and only used if you want to set the request timeout manually.
    |
    */

    'timeout' => env('DOCUWARE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | DocuWare Credentials
    |--------------------------------------------------------------------------
    |
    | Before you can communicate with the DocuWare REST-API it is necessary
    | to enter your credentials. You should specify a url containing the
    | scheme and hostname. In addition add your username and password.
    |
    */

    'credentials' => [
        'url' => env('DOCUWARE_URL'),
        'username' => env('DOCUWARE_USERNAME'),
        'password' => env('DOCUWARE_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Passphrase
    |--------------------------------------------------------------------------
    |
    | In order to create encrypted URLs we need a passphrase. This enables a
    | secure exchange of DocuWare URLs without anyone being able to modify
    | your query strings. You can find it in the organization settings.
    |
    */

    'passphrase' => env('DOCUWARE_PASSPHRASE'),

    /*
    |--------------------------------------------------------------------------
    | Authentication Cookie Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of minutes the authentication cookie is
    | valid. Afterwards it will be removed from the cache and you need to
    | provide a fresh one. By default, the lifetime lasts for one year.
    |
    */

    'cookie_lifetime' => (int) env('DOCUWARE_COOKIE_LIFETIME', 525600),

    /*
    |--------------------------------------------------------------------------
    | Configurations
    |--------------------------------------------------------------------------
    |
    */
    'configurations' => [
        'search' => [
            'operation' => 'And',

            /*
             * Force Refresh
             * Determine if result list is retrieved from the cache when ForceRefresh is set
             * to false (default) or always a new one is executed when ForceRefresh is set to true.
             */

            'force_refresh' => false,
            'include_suggestions' => false,
            'additional_result_fields' => [],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Tests
    |--------------------------------------------------------------------------
    |
    */
    'tests' => [
        'file_cabinet_id' => env('DOCUWARE_TESTS_FILE_CABINET_ID'),
        'dialog_id' => env('DOCUWARE_TESTS_DIALOG_ID'),
        'basket_id' => env('DOCUWARE_TESTS_BASKET_ID'),
        'section' => (int) env('DOCUWARE_TESTS_SECTION'),
        'organization_id' => env('DOCUWARE_TESTS_ORGANIZATION_ID'),
        'document_id' => (int) env('DOCUWARE_TESTS_DOCUMENT_ID'),
        'document_file_size_preview' => (int) env('DOCUWARE_TESTS_DOCUMENT_FILE_SIZE_PREVIEW'),
        'document_file_size' => (int) env('DOCUWARE_TESTS_DOCUMENT_FILE_SIZE'),
        'document_count' => (int) env('DOCUWARE_TESTS_DOCUMENT_COUNT'),
        'document_thumbnail_file_size' => (int) env('DOCUWARE_TESTS_DOCUMENT_THUMBNAIL_FILE_SIZE'),
        'document_ids' => json_decode(env('DOCUWARE_TESTS_DOCUMENTS_IDS', '[]')),
        'documents_file_size' => (int) env('DOCUWARE_TESTS_DOCUMENTS_FILE_SIZE'),
        'field_name' => env('DOCUWARE_TESTS_FIELD_NAME'),
        'field_name_2' => env('DOCUWARE_TESTS_FIELD_NAME_2'),
    ],
];
```

## 🚧 Testing

Copy your own phpunit.xml-file.

```bash
cp phpunit.xml.dist phpunit.xml
```

Modify environment variables in the phpunit.xml-file:

```xml

<env name="DOCUWARE_URL" value="https://domain.docuware.cloud"/>
<env name="DOCUWARE_USERNAME" value="user@domain.test"/>
<env name="DOCUWARE_PASSWORD" value="password"/>
<env name="DOCUWARE_PASSPHRASE" value="passphrase"/>
<env name="DOCUWARE_COOKIES" value="cookies"/>
<env name="DOCUWARE_TIMEOUT" value="30"/>

<env name="DOCUWARE_TESTS_FILE_CABINET_ID" value=""/>
<env name="DOCUWARE_TESTS_DIALOG_ID" value=""/>
<env name="DOCUWARE_TESTS_BASKET_ID" value=""/>
<env name="DOCUWARE_TESTS_SECTION" value=""/>
<env name="DOCUWARE_TESTS_FIELD_NAME" value="UUID"/>
<env name="DOCUWARE_TESTS_FIELD_NAME_2" value="DOCUMENT_LABEL"/>

<env name="DOCUWARE_TESTS_DOCUMENT_FILE_SIZE_PREVIEW" value=""/>
<env name="DOCUWARE_TESTS_DOCUMENT_FILE_SIZE" value=""/>
<env name="DOCUWARE_TESTS_DOCUMENT_COUNT" value=""/>
<env name="DOCUWARE_TESTS_DOCUMENT_THUMBNAIL_FILE_SIZE" value=""/>
<env name="DOCUWARE_TESTS_DOCUMENTS_FILE_SIZE" value=""/>
<env name="DOCUWARE_TESTS_DOCUMENT_ID" value=""/>
<env name="DOCUWARE_TESTS_DOCUMENTS_IDS" value="[]"/>
```

Run the tests:

```bash
composer test
```

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## ✏️ Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## 🧑‍💻 Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## 🙏 Credits

- [Sebastian Fix](https://github.com/StanBarrows)
- [Faissal Wahabali](https://github.com/faissaloux)
- [Ruslan Steiger](https://github.com/SuddenlyRust)
- [Rhys Lees](https://github.com/RhysLees)
- [All Contributors](../../contributors)
- [Skeleton Repository from Spatie](https://github.com/spatie/package-skeleton-laravel)
- [Laravel Package Training from Spatie](https://spatie.be/videos/laravel-package-training)

## 🎭 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
