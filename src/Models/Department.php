<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = true; // live has created_at/updated_at (nullable)
    protected $fillable = [
        'companyname','companylogo','country','state','city','zipcode',
        'address1','address2','dayphone','nightphone','fax','po','history',
        'competitive_battleground','competitive_startdate','competitive_enddate',
        'sales_forecast','sales_startdate','sales_enddate','master_id','active',
    ];


    /**
     * Users who belong to this department via the team_users pivot.
     * Pivot includes: team_id, role, timestamps.
     */
    public function users()
    {
        return $this->belongsToMany(\Eauto\Core\Models\User::class, 'team_users', 'department_id', 'user_id')
            ->withPivot(['team_id', 'role'])
            ->withTimestamps();
    }

    /**
     * Teams associated to this department via the team_users pivot.
     * Pivot includes: user_id, role, timestamps.
     */
    public function teams()
    {
        return $this->belongsToMany(\Eauto\Core\Models\Team::class, 'team_users', 'department_id', 'team_id')
            ->withPivot(['user_id', 'role'])
            ->withTimestamps();
    }
}
