<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Searchable;

    protected $fillable = ['name','email','password'];
    protected $hidden   = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'admin'             => 'boolean',
        ];
    }

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

    public function switchToTeam(\Eauto\Core\Models\Team $team): void
    {
        if (! $this->teams()->whereKey($team->id)->exists()) {
            abort(403, 'You do not belong to that team.');
        }

        $this->forceFill(['current_team_id' => $team->id])->save();
    }
}