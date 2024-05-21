<?php

namespace App\Http\Requests\User\PrefessionalExperience;

use App\Domain\Job\Enum\EmploymentTypeEnum;
use App\Domain\Job\Enum\WorkModelEnum;
use App\Http\Requests\AbstractRequest;

class AddProfessionalExperienceRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'professional_experiences' => 'required|array|min:1',
            'professional_experiences.*.company_name' => 'required|string',
            'professional_experiences.*.company_id' => 'integer',
            'professional_experiences.*.position' => 'required|string',
            'professional_experiences.*.description' => 'string',
            'professional_experiences.*.start_date' => 'required|date|date_format:Y-m-d',
            'professional_experiences.*.end_date' => 'date|date_format:Y-m-d',
            'professional_experiences.*.is_current' => 'required|boolean',
            'professional_experiences.*.location' => 'string',
            'professional_experiences.*.work_model' =>
                'nullable|in:' . implode(',', array_column(WorkModelEnum::cases(), 'value')),
            'professional_experiences.*.employment_type' =>
                'nullable|in:' . implode(',', array_column(EmploymentTypeEnum::cases(), 'value')),
        ];
    }
}
