<?php

namespace App\Http\Requests\User\UserCompetence;

use App\Http\Requests\AbstractRequest;
use App\Http\Requests\Rules\UniqueArrayValuesRule;

class DeleteCompetencesRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competences_id' => [
                'required',
                'array',
                'min:1',
                new UniqueArrayValuesRule,
            ],
            'competences_id.*' => 'required|integer',
        ];
    }
}
