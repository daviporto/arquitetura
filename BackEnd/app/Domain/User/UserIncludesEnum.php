<?php

namespace App\Domain\User;

enum UserIncludesEnum: string
{
    case Competences = 'competences';
    case AcademicRecords = 'academicRecords';
    case ProfessionalExperiences = 'professionalExperiences';
}
