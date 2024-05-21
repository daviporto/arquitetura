<?php

namespace App\Exceptions;

enum ExceptionMessagesEnum: string
{
    case CompanyNotFound = 'Company not found';
    case CnpjMustHaveFourteenDigits = 'Cnpj must have twelve digits';
    case CompanyIdMustBeAnInteger = 'Company id must be an integer';

    case JobNotFound = 'Job not found';
    case IdRequiredToUpdateJob = 'Job id is required to update job';
    case JobIdMustBeAnInteger = 'Job id must be an integer';
    case OnlyOwnerCanDeleteJob = 'Only owner can delete job';
    case OnlyOwnerCanUpdateJob = 'Only owner can update job';
    case JobAcceptApplicationUntilPassed = 'Job accept application deadline is in the past';
    case JobApplicationsAmountSurpassedException = 'Job applications amount surpassed';

    case JobApplicationStatusNotAllowedException = 'Job application status not allowed';
    case JobApplicationUnknownEnumOptionException = 'Job application unknown enum option';
    case JobsIdFilterMustBePositiveInteger = 'Jobs id must be positive integers, in job applications';
    case CandidatesIdFilterMustBePositiveInteger = 'Candidates id must be positive integers, in job applications';
    case FilterDateFromMustBeDateAfter = 'Filter date from must be date after, in job applications';
    case JobApplicationNotFound = 'Job application not found';
    case JobApplicationStatusIsFinalException = 'Job application status is final, cannot be changed';
    case JobApplicationIdMustBeAnInteger = 'Job application id must be an integer';
    case CandidateCanOnlyUpdateStatusToCanceled = 'Candidate can only update status to canceled';
    case RecruiterCannotCancelJobApplication = 'Recruiter cannot cancel job application';

    case UserIdMustBeAnInteger = 'User id must be an integer';
    case UserEmailNotAvailable = 'User email is not available';
    case UserPhoneNotAvailable = 'User phone is not available';
    case UserNotFound = 'User not found';
    case UserDoesntHaveCompetence = 'User doesn\'t have competence';

    case CompetenceNotFound = 'Competence not found';

    case AcademicRecordMustHaveEndDateWhenUnfinished = 'Academic record must have end date when unfinished';
    case AcademicRecordEndDateMustBeAfterStartDate = 'Academic record end date must be after start date';
    case AcademicRecordNotFound = 'Academic record not found';
    case OnlyOwnerCanDeleteAcademicRecord = 'Only owner can delete academic record';

    case ProfessionalExperienceMustHaveEndDateWhenFinished = 'Professional experience must have end date when finished';
    case ProfessionalExperienceEndDateMustBeAfterStartDate = 'Professional experience end date must be after start date';
    case ProfessionalExperienceEndDateWhenCurrentMustBeInTheFuture = 'Professional experience end date when current must be in the future';
    case ProfessionalExperienceNotFound = 'Professional experience not found';
    case OnlyOwnerCanDeleteProfessionalExperience = 'Only owner can delete professional experience';

    case TokenExpiredException = 'Token expired';
    case TokenInvalidException = 'Token invalid';
    case OnlyForRecruitersException = 'Only for recruiters';

    case ResumeTypeNotAllowedException = 'Resume type not allowed';
    case ResumeIdMustBeAnInteger = 'Resume id must be an integer';
    case ResumeIdMustBePositive = 'Resume id must be positive';
    case ResumeNotFound = 'Resume not found';
    case OnlyOwnerCanUpdateResumeAlias = 'Only owner can update resume alias';
    case OnlyOwnerCanDownloadResume = 'Only owner can download resume';
    case OnlyOwnerCanUpdateResumeFile = 'Only owner can update resume file';
    case OnlyOwnerCanSeeResume = 'Only owner can see resume';
    case OnlyOwnerCanDeleteResume = 'Only owner can delete resume';
}
