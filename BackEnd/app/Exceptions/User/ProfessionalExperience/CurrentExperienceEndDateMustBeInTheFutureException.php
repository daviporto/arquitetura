<?php

namespace App\Exceptions\User\ProfessionalExperience;

use App\Exceptions\Abstract\AbstractFindMeException;
use App\Exceptions\ExceptionMessagesEnum;

class CurrentExperienceEndDateMustBeInTheFutureException extends AbstractFindMeException
{
    public function __construct()
    {
        parent::__construct(ExceptionMessagesEnum::ProfessionalExperienceEndDateWhenCurrentMustBeInTheFuture->value);
    }
}
