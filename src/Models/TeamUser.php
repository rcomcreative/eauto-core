<?php

namespace Eauto\Core\Models;

use Database\Factories\TeamUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Laravel\Scout\Searchable;

class TeamUser extends Pivot
{
    /** @use HasFactory<TeamUserFactory> */
    use HasFactory, Searchable;

    protected $table = 'team_users';

    protected $fillable = ['team_id', 'user_id', 'department_id', 'role', 'active'];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    // app/Models/TeamUser.php
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
