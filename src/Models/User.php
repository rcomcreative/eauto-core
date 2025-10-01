<?php

namespace Eauto\Core\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Log;
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Searchable, HasRoles;

    /** Spatie\Permission\Traits\HasRoles;
     */
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'admin' => 'boolean',
        ];
    }

    /**
     * @param \Filament\Panel $panel
     * @return bool
     * team_id, current_team_id useage
     */
//    public function canAccessPanel(\Filament\Panel $panel): bool
//    {
//        return $this->hasAnyRole(['super_owner','org_owner','admin']);
//    }

    /** Spaite\Permission\Traits\HasRoles
     */
    public function teams()
    {
        return $this->belongsToMany(\Eauto\Core\Models\Team::class, 'team_users')
            ->withPivot('role')
            ->withTimestamps();
    }


    public function currentTeam()
    {
        return $this->belongsTo(\Eauto\Core\Models\Team::class, 'current_team_id');
    }

    /**
     * Change change team_id to current_team_id
     */
    // ---- Helpers ----
    public function switchToTeam(Team $team): void
    {
        if (! $this->teams()->whereKey($team->id)->exists()) {
            abort(403, 'You do not belong to that team.');
        }

        $this->forceFill(['current_team_id' => $team->id])->save();
    }

    // Filament access (platform roles). These are GLOBAL roles (no team).
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Gather context for debugging
        $teamId = optional($this->currentTeam)->id ?? $this->teams()->value('teams.id');
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($teamId);
        $roles = $this->getRoleNames()->toArray();

        // Decision: allow based on platform roles or admin flag
        $allowed = $this->hasAnyRole(['super', 'super_owner', 'org_owner', 'admin'])
            || ($this->admin ?? false);

        // Log for troubleshooting (remove after confirming access works)
//        Log::debug('User::canAccessPanel check', [
//            'user_id' => $this->id,
//            'email' => $this->email,
//            'team_id' => $teamId,
//            'roles' => $roles,
//            'admin' => $this->admin ?? null,
//            'allowed' => $allowed,
//            'guard' => $this->guard_name ?? 'web',
//        ]);

        return $allowed;
    }


}
