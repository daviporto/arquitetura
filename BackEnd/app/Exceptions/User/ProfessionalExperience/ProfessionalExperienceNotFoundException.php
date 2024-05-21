<?php

namespace App\Exceptions\User\ProfessionalExperience;

use App\Exceptions\Abstract\AbstractFindMeException;
use App\Exceptions\ExceptionMessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class ProfessionalExperienceNotFoundException extends AbstractFindMeException
{
    public function __construct(?int $id)
    {
        parent::__construct(
            ExceptionMessagesEnum::ProfessionalExperienceNotFound->value,
            Response::HTTP_NOT_FOUND
        );

        $this->additionalInfo = [
            'professional_experience_id' => $id
        ];
    }
}
