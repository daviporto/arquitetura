<?php

namespace App\Http\Requests\User\AcademicRecord;

use App\Http\Requests\AbstractRequest;
use App\Http\Requests\Rules\UniqueArrayValuesRule;

class DeleteAcademicRecordsRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_records_id' => [
                'required',
                'array',
                'min:1',
                new UniqueArrayValuesRule,
            ],
            'academic_records_id.*' => 'required|integer',
        ];
    }
}
