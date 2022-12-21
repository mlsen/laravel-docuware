<?php

return [

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

    'cookie_lifetime' => (int)env('DOCUWARE_COOKIE_LIFETIME', 525600),

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
 | Configurations
 |--------------------------------------------------------------------------
 |
 */
    'tests' => [
        'url' => 'https://codebar-invoice-demo.docuware.cloud',
        'file_cabinet_id' => '4ca593b2-c19d-4399-96e6-c90168dbaa97',
        'dialog_id' => '4fc78419-37f4-409b-ab08-42e5cecdee92',
        'basket_id' => 'b_da50d356-3ed5-4699-a8ab-d3fbeb855a4c',
        'document_id' => 1,
        'preview_document_file_size' => 266332,
        'document_file_size' => 190305,
        'document_ids' => [1, 2],
        'documents_file_size' => 132587,
        'field_name' => 'UUID',
    ],
];
