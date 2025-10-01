<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Scout\Searchable;

class TeamUser extends Pivot
{
    /** @use HasFactory<\Database\Factories\TeamUserFactory> */
    use HasFactory, Searchable;

    protected $table = 'team_user';
    protected $fillable = ['team_id', 'user_id', 'role'];

}
