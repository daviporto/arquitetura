<?php

namespace App\Http\Requests\User;

use App\Domain\User\UserIncludesEnum;

class ShowUserRequest extends UserRequestHavingId
{
    protected array $availableIncludes = [
        UserIncludesEnum::Competences->value,
        UserIncludesEnum::AcademicRecords->value,
        UserIncludesEnum::ProfessionalExperiences->value,
    ];

    public function rules(): array
    {
        return [
            'includes' => 'array',
            'includes.*' => 'string|in:' . implode(',', $this->availableIncludes),
        ];
    }
}
