<?php

namespace CodebarAg\DocuWare\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetSelectListRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $fileCabinetId,
        protected readonly string $dialogId,
        protected readonly string $fieldName,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/FileCabinets/'.$this->fileCabinetId.'/Query/SelectListExpression';
    }

    public function defaultQuery(): array
    {
        return [
            'dialogId' => $this->dialogId,
            'fieldName' => $this->fieldName,
        ];
    }
}