<?php

use CodebarAg\DocuWare\Connectors\DocuWareConnector;
use Illuminate\Support\Arr;

it('returns the correct default config', function () {
    $connector = new DocuWareConnector();

    $timeout = Arr::get($connector->defaultConfig(), 'timeout');
    //$cookies = Arr::get($connector->defaultConfig(), 'cookies');

    expect($timeout)->toBe(config('docuware.timeout'));
    //expect($cookies)->toBe(Auth::cookieJar());

})->group('connector');

it('returns the correct base url', function () {
    $connector = new DocuWareConnector();

    expect($connector->resolveBaseUrl())->toBe(config('docuware.credentials.url').'/DocuWare/Platform');
})->group('connector');

it('returns the correct default headers', function () {
    $connector = new DocuWareConnector();

    expect($connector->defaultHeaders())->toBe([
        'Accept' => 'application/json',
    ]);
})->group('connector');
