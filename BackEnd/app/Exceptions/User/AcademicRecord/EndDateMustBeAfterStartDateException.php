<?php

namespace App\Exceptions\User\AcademicRecord;

use App\Exceptions\Abstract\AbstractFindMeException;
use App\Exceptions\ExceptionMessagesEnum;
use Carbon\Carbon;

class EndDateMustBeAfterStartDateException extends AbstractFindMeException
{
    public function __construct(Carbon $startDate, Carbon $endDate)

    {
        parent::__construct(ExceptionMessagesEnum::AcademicRecordEndDateMustBeAfterStartDate->value);

        $this->additionalInfo = [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ];
    }
}
