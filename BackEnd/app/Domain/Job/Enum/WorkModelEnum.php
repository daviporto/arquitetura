<?php

namespace App\Domain\Job\Enum;

enum WorkModelEnum: string
{
    case Hybrid = 'hybrid';
    case OnSite= 'onSite';
    case HomeOffice = 'homeOffice';
}
