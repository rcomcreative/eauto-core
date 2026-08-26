<?php

namespace Eauto\Core\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory, Searchable;

    protected $fillable = ['name', 'is_active', 'renewal_date', 'external_account_ref', 'notes'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->withPivot(['department_id', 'role', 'active'])
            ->withTimestamps();
    }

    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function invites()
    {
        return $this->hasMany(TeamInvitation::class);
    }
}
