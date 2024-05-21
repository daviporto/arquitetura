<?php

namespace App\Models;

use Database\Factories\ProfessionalExperienceFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ProfessionalExperience
 *
 * @property int $id
 * @property string $company_name
 * @property string $position
 * @property string|null $description
 * @property string $start_date
 * @property string|null $end_date
 * @property bool $is_current
 * @property string|null $location
 * @property string|null $work_model
 * @property string|null $employment_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Job newModelQuery()
 * @method static Builder|Job newQuery()
 * @method static Builder|Job query()
 * @method static Builder|Job whereAcceptApplicationUntil($value)
 * @method static Builder|Job whereApplicationsAmount($value)
 * @method static Builder|Job whereCreatedAt($value)
 * @method static Builder|Job whereDeletedAt($value)
 * @method static Builder|Job whereDescription($value)
 * @method static Builder|Job whereEmploymentType($value)
 * @method static Builder|Job whereId($value)
 * @method static Builder|Job whereIsAvailable($value)
 * @method static Builder|Job whereLocation($value)
 * @method static Builder|Job whereName($value)
 * @method static Builder|Job whereOwner($value)
 * @method static Builder|Job whereSalary($value)
 * @method static Builder|Job whereSalaryTimeUnit($value)
 * @method static Builder|Job whereUpdatedAt($value)
 * @method static Builder|Job whereWeekWorkload($value)
 * @method static Builder|Job whereWorkModel($value)
 * @property int $user_id
 * @method static ProfessionalExperienceFactory factory($count = null, $state = [])
 * @method static Builder|ProfessionalExperience whereCompanyName($value)
 * @method static Builder|ProfessionalExperience whereEndDate($value)
 * @method static Builder|ProfessionalExperience whereIsCurrent($value)
 * @method static Builder|ProfessionalExperience wherePosition($value)
 * @method static Builder|ProfessionalExperience whereStartDate($value)
 * @method static Builder|ProfessionalExperience whereUserId($value)
 * @mixin Eloquent
 */
class ProfessionalExperience extends Model
{
    use HasFactory;

    protected $table = 'professional_experiences';

    protected $fillable = [
        'user_id',
        'company_name',
        'company_id',
        'position',
        'description',
        'start_date',
        'end_date',
        'is_current',
        'location',
        'work_model',
        'employment_type'
    ];
}
