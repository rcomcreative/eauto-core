<?php

namespace Eauto\Core\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Searchable;

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

    /** Spaite\Permission\Traits\HasRoles
     */
    public function teams()
    {
        return $this->belongsToMany(\Eauto\Core\Models\Team::class, 'team_users',)
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
    public function switchToTeam(\Eauto\Core\Models\Team $team): void
    {
        if (! $this->teams()->whereKey($team->id)->exists()) {
            abort(403, 'You do not belong to that team.');
        }

        $this->forceFill(['current_team_id' => $team->id])->save();
    }


}
