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
}
