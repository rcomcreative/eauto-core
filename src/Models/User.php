<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, Searchable;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'initials',
        'email',
        'password',
        'phone_number',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',  // you can pass a plain string; it will be hashed
            'admin' => 'boolean',
        ];
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users')
            ->withPivot(['department_id', 'role', 'active'])
            ->withTimestamps();
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'team_users', 'user_id', 'department_id')
            ->withPivot(['team_id', 'role', 'active'])
            ->withTimestamps();
    }

    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function sessionDurationPolicies()
    {
        return $this->hasMany(SessionDurationPolicy::class, 'scope_id')
            ->where('scope_type', SessionDurationPolicy::SCOPE_USER);
    }

    public function currentTeam()
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function switchToTeam(Team $team): void
    {
        if (! $this->teams()->whereKey($team->id)->exists()) {
            abort(403, 'You do not belong to that team.');
        }

        $this->forceFill(['current_team_id' => $team->id])->save();
    }

    /**
     * Forecast releases validated by this user.
     */
    public function validatedForecastReleases()
    {
        return $this->hasMany(ForecastRelease::class, 'validated_by');
    }
}
