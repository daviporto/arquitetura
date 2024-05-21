<?php

namespace App\Domain\User\ProfessionalExperience;

enum WorkModelEnum: string
{
    case Hybrid = 'hybrid';
    case OnSite = 'onSite';
    case HomeOffice = 'homeOffice';
}
