<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class TeamInvitation extends Model
{
    /** @use HasFactory<\Database\Factories\TeamInvitationFactory> */
    use HasFactory, Searchable;

    protected $fillable = ['team_id','email','role','token','expires_at'];

    public function team() { return $this->belongsTo(Team::class); }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }
}
