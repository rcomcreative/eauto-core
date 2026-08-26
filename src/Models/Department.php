<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = true; // live has created_at/updated_at (nullable)

    protected $fillable = [
        'companyname', 'companylogo', 'avatar_color', 'country', 'state', 'city', 'zipcode',
        'address1', 'address2', 'dayphone', 'nightphone', 'fax', 'po', 'history', 'contact_email', 'send_welcome_email',
        'competitive_battleground', 'competitive_startdate', 'competitive_enddate',
        'sales_forecast', 'sales_startdate', 'sales_enddate', 'master_id', 'active',
    ];

    /**
     * Users who belong to this department via the team_users pivot.
     * Pivot includes: team_id, role, timestamps.
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'team_users',
            'department_id',
            'user_id'
        )
            ->withPivot(['team_id', 'role', 'active'])
            ->withTimestamps();
    }

    /**
     * Teams associated to this department via the team_users pivot.
     * Pivot includes: user_id, role, timestamps.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users', 'department_id', 'team_id')
            ->withPivot(['user_id', 'role'])
            ->withTimestamps();
    }

    public function customSegments()
    {
        return $this->hasMany(DepartmentCustomSegment::class, 'department_id');
    }

    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function sessionDurationPolicies()
    {
        return $this->hasMany(SessionDurationPolicy::class, 'scope_id')
            ->where('scope_type', SessionDurationPolicy::SCOPE_DEPARTMENT);
    }
}
