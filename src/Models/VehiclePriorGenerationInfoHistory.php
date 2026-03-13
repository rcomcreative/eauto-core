<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePriorGenerationInfoHistory extends Model
{
    use HasFactory;

    protected $table = 'vehicle_prior_generation_infos_history';

    protected $fillable = [
        'source_id',
        'admin_id',
        'vehicle_id',
        'priorGenerationInfo',
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
        return $this->belongsTo(VehiclePriorGenerationInfo::class, 'source_id');
    }
}