<?php

namespace App\Exceptions\User\ProfessionalExperience;

use App\Exceptions\Abstract\AbstractDomainException;
use App\Exceptions\ExceptionMessagesEnum;
use Carbon\Carbon;

class EndDateMustBeAfterStartDateException extends AbstractDomainException
{
    public function __construct(Carbon $startDate, Carbon $endDate)

    {
        parent::__construct(ExceptionMessagesEnum::ProfessionalExperienceEndDateMustBeAfterStartDate->value);

        $this->additionalInfo = [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ];
    }
}
