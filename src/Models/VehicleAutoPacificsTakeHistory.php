<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleAutoPacificsTakeHistory extends Model
{
    use HasFactory;

    protected $table = 'vehicle_auto_pacifics_takes_history';

    protected $fillable = [
        'source_id',
        'admin_id',
        'vehicle_id',
        'autopacificsTake',
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
        return $this->belongsTo(VehicleAutoPacificsTake::class, 'source_id');
    }
}