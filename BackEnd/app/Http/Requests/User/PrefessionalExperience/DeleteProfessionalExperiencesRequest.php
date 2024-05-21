<?php

namespace App\Http\Requests\User\PrefessionalExperience;

use App\Http\Requests\AbstractRequest;
use App\Http\Requests\Rules\UniqueArrayValuesRule;

class DeleteProfessionalExperiencesRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'professional_experiences_id' => [
                'required',
                'array',
                'min:1',
                new UniqueArrayValuesRule,
            ],
            'professional_experiences.*' => 'required|integer',
        ];
    }
}
