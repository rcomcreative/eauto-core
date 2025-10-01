<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory, Searchable;

    protected $fillable = ['name','is_active','renewal_date','external_account_ref','notes'];

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'team_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function invites()
    {
        return $this->hasMany(TeamInvitation::class);
    }
}
