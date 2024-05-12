<?php

namespace App\Domain\JobApplications;

enum JobApplicationsStatusEnum: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Canceled = 'canceled';
    case Hired = 'hired';
    case InProgress = 'in_progress';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
