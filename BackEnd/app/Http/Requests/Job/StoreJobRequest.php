<?php

namespace App\Http\Requests\Job;

use App\Domain\Competence\Enum\CompetenceTypesEnum;
use App\Domain\Job\Enum\EmploymentTypeEnum;
use App\Domain\Job\Enum\SalaryTimeUnitEnum;
use App\Domain\Job\Enum\WorkModelEnum;
use App\Http\Requests\AbstractRequest;

class StoreJobRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'is_available' => 'required|boolean',
            'applications_amount' => 'required|integer|min:0',
            'salary' => 'nullable|integer|min:0',
            'salary_time_unit' =>
                'required_with:salary|in:' . implode(',', array_column(SalaryTimeUnitEnum::cases(), 'value')),
            'accept_application_until' => 'nullable|date|date_format:Y-m-d H:i:s',
            'work_model' =>
                'nullable|in:' . implode(',', array_column(WorkModelEnum::cases(), 'value')),
            'employment_type' =>
                'nullable|in:' . implode(',', array_column(EmploymentTypeEnum::cases(), 'value')),
            'week_workload' => 'nullable|integer|min:0',
            'location' => 'nullable|string',
            'company_id' => 'required|int|min:1',

            'competences' => 'nullable|array',
            'competences.*.name' => 'required|string',
            'competences.*.description' => 'string',
            'competences.*.type' => 'string|in: ' . CompetenceTypesEnum::getValuesAsString()
        ];
    }
}
