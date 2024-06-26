<?php

namespace App\Http\Requests\Resume;

use App\Exceptions\Resume\ResumeIdMustBeAnIntegerException;
use App\Exceptions\Resume\ResumeIdMustBePositiveException;
use App\Http\Requests\AbstractRequest;

abstract class ResumeRequestHavingId extends AbstractRequest
{
    /**
     * @throws ResumeIdMustBeAnIntegerException
     * @throws ResumeIdMustBePositiveException
     */
    public function getResumeId(): int
    {
        $resumeId = $this->route('resume');

        if (!is_numeric($resumeId)) {
            throw new ResumeIdMustBeAnIntegerException($resumeId);
        }

        if ($resumeId <= 0) {
            throw new ResumeIdMustBePositiveException($resumeId);
        }

        return $resumeId;
    }
}
