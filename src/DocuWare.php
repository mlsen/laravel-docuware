<?php

namespace codebar\DocuWare;

use Carbon\Carbon;
use codebar\DocuWare\DTO\Dialog;
use codebar\DocuWare\DTO\Document;
use codebar\DocuWare\DTO\Field;
use codebar\DocuWare\DTO\FileCabinet;
use codebar\DocuWare\Exceptions\UnableToDownloadDocuments;
use codebar\DocuWare\Support\ParseValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DocuWare
{
    const COOKIE_NAME = '.DWPLATFORMAUTH';

    protected string $domain;

    public function __construct()
    {
        $this->domain = ParseValue::domain();
    }

    public function login(): string
    {
        if (Cache::has('docuware.cookies')) {
            return Cache::get('docuware.cookies');
        }

        $url = sprintf(
            '%s/docuware/platform/Account/Logon',
            config('docuware.url'),
        );

        $response = Http::asForm()
            ->acceptJson()
            ->post($url, [
                'UserName' => config('docuware.user'),
                'Password' => config('docuware.password'),
            ])
            ->throw();

        $cookie = collect($response->cookies()->toArray())
            ->reject(fn (array $cookie) => $cookie['Value'] === '')
            ->firstWhere('Name', self::COOKIE_NAME);

        Cache::put(
            'docuware.cookies',
            [self::COOKIE_NAME => $cookie['Value']],
            now()->addDay(),
        );

        return $cookie['Value'];
    }

    public function logout(): void
    {
        $cookie = Cache::pull('docuware.cookies');

        $url = sprintf(
            '%s/docuware/platform/Account/Logoff',
            config('docuware.url'),
        );

        Http::withCookies($cookie, $this->domain)->get($url);
    }

    public function getFileCabinets(): Collection
    {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets?orgid=1',
            config('docuware.url'),
        );

        $cabinets = Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->json('FileCabinet');

        return collect($cabinets)->map(fn (array $cabinet) => FileCabinet::fromJson($cabinet));
    }

    public function getFields(string $fileCabinetId): Collection
    {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s',
            config('docuware.url'),
            $fileCabinetId,
        );

        $fields = Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->json('Fields');

        return collect($fields)->map(fn (array $field) => Field::fromJson($field));
    }

    public function getDialogs(string $fileCabinetId): Collection
    {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Dialogs',
            config('docuware.url'),
            $fileCabinetId,
        );

        $dialogs = Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->json('Dialog');

        return collect($dialogs)->map(fn (array $dialog) => Dialog::fromJson($dialog));
    }

    public function getSelectList(
        string $fileCabinetId,
        string $dialogId,
        string $fieldName,
    ): array {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Query/SelectListExpression?dialogId=%s&fieldName=%s',
            config('docuware.url'),
            $fileCabinetId,
            $dialogId,
            $fieldName,
        );

        return Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->json('Value');
    }

    public function getDocument(string $fileCabinetId, int $documentId): Document
    {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Documents/%s',
            config('docuware.url'),
            $fileCabinetId,
            $documentId,
        );

        $response = Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->json();

        return Document::fromJson($response);
    }

    public function getDocumentPreview(
        string $fileCabinetId,
        int $documentId,
    ): string {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Documents/%s/Image',
            config('docuware.url'),
            $fileCabinetId,
            $documentId,
        );

        return Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->body();
    }

    public function downloadDocument(
        string $fileCabinetId,
        int $documentId,
    ): string {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Documents/%s/FileDownload?targetFileType=Auto&keepAnnotations=false',
            config('docuware.url'),
            $fileCabinetId,
            $documentId
        );

        return Http::withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->body();
    }

    public function downloadDocuments(
        string $fileCabinetId,
        array $documentIds,
    ): string {
        throw_if(
            count($documentIds) < 2,
            UnableToDownloadDocuments::selectAtLeastTwoDocuments(),
        );

        $firstDocumentId = $documentIds[0];
        $additionalDocumentIds = implode(',', array_slice($documentIds, 1));

        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Documents/%s/FileDownload?&keepAnnotations=false&downloadFile=true&autoPrint=false&append=%s',
            config('docuware.url'),
            $fileCabinetId,
            $firstDocumentId,
            $additionalDocumentIds,
        );

        return Http::withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->get($url)
            ->throw()
            ->body();
    }

    public function updateDocumentValue(
        string $fileCabinetId,
        int $documentId,
        string $fieldName,
        string $newValue,
    ): null | int | float | Carbon | string {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Documents/%s/Fields',
            config('docuware.url'),
            $fileCabinetId,
            $documentId,
        );

        $fields = Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->put($url, [
                'Field' => [
                    [
                        'FieldName' => $fieldName,
                        'Item' => $newValue,
                    ],
                ],
            ])
            ->throw()
            ->json('Field');

        $field = collect($fields)->firstWhere('FieldName', $fieldName);

        return ParseValue::field($field);
    }

    public function uploadDocument(
        string $fileCabinetId,
        string $fileContent,
        string $fileName,
    ): Document {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Documents',
            config('docuware.url'),
            $fileCabinetId,
        );

        $response = Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->attach('file', $fileContent, $fileName)
            ->post($url)
            ->throw()
            ->json();

        return Document::fromJson($response);
    }

    public function deleteDocument(
        string $fileCabinetId,
        int $documentId,
    ): void {
        $url = sprintf(
            '%s/docuware/platform/FileCabinets/%s/Documents/%s',
            config('docuware.url'),
            $fileCabinetId,
            $documentId,
        );

        Http::acceptJson()
            ->withCookies(Cache::get('docuware.cookies'), $this->domain)
            ->delete($url)
            ->throw();
    }

    public function search(): DocuWareSearch
    {
        return (new DocuWareSearch());
    }
}
