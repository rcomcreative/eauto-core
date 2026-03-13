<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCGLaunchInfoHistory extends Model
{
    use HasFactory;

    protected $table = 'vehicle_c_g_launch_infos_history';

    protected $fillable = [
        'source_id',
        'admin_id',
        'vehicle_id',
        'currentGenerationLaunchInfo',
        'entrydate',
        'modified',
        'live',
        'edited_by_admin_id',
        'archived_at',
    ];

    protected $casts = [
        'entrydate' => 'date',
        'modified' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function source()
    {
        return $this->belongsTo(VehicleCGLaunchInfo::class, 'source_id');
    }
}