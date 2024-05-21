<?php

namespace App\Domain\User\ProfessionalExperience;

enum EmploymentTypeEnum: string
{
    case FullTime = 'full-time';
    case PartTime = 'part-time';
}
