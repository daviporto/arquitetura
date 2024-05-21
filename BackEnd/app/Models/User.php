<?php

namespace App\Models;

use App\Helpers\File\FileHelperInterface;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $type
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $about_me
 * @property string $profile_picture_path
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read Collection<Competence> $competences
 * @property-read int|null $tokens_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property-read int|null $posts_count
 * @method static Builder|User whereType($value)
 * @property-read Collection<int, \App\Models\AcademicRecord> $academicRecords
 * @property-read int|null $academic_records_count
 * @property-read int|null $competences_count
 * @property-read Collection<int, \App\Models\ProfessionalExperience> $professionalExperiences
 * @property-read int|null $professional_experiences_count
 * @method static Builder|User whereAboutMe($value)
 * @method static Builder|User whereProfilePicturePath($value)
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'about_me',
        'profile_picture_path'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(
            Competence::class,
            'competence_user',
            'user_id',
            'competence_id'
        );
    }

    public function academicRecords(): HasMany
    {
        return $this->hasMany(AcademicRecord::class);
    }

    public function professionalExperiences(): HasMany
    {
        return $this->hasMany(ProfessionalExperience::class);
    }

    public function getProfilePicturePathAttribute(): ?string
    {
        if ($path = $this->attributes['profile_picture_path']) {
            return app(FileHelperInterface::class)->getUrlForPublicFile($path);
        } else {
            return null;
        }
    }
}
