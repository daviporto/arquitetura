<?php

namespace App\Exceptions\User\ProfessionalExperience;

use App\Exceptions\Abstract\AbstractFindMeException;
use App\Exceptions\ExceptionMessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class OnlyOwnerCanDeleteProfessionalExperienceException extends AbstractFindMeException
{
    public function __construct(?int $id)
    {
        parent::__construct(
            ExceptionMessagesEnum::OnlyOwnerCanDeleteProfessionalExperience->value,
            Response::HTTP_UNAUTHORIZED
        );

        $this->additionalInfo = [
            'professional_experience_id' => $id
        ];
    }
}
