<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCGEquipUpdateHistory extends Model
{
    use HasFactory;

    protected $table = 'vehicle_c_g_equip_updates_history';

    protected $fillable = [
        'source_id',
        'admin_id',
        'vehicle_id',
        'currentGenerationEquipmentUpdates',
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
        return $this->belongsTo(VehicleCGEquipUpdate::class, 'source_id');
    }
}