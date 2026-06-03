<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Eauto\Core\Models\Traits\ArchivesNarrativeHistory;

class VehicleCGEquipUpdate extends Model
{
    use HasFactory, Searchable, ArchivesNarrativeHistory;

    protected $table = 'vehicle_c_g_equip_updates';

    protected $fillable = [
        'id',
        'admin_id',
        'vehicle_id',
        'currentGenerationEquipmentUpdates',
        'entrydate',
        'modified',
        'live',
    ];

    protected $casts = [
        'entrydate' => 'date',
        'modified' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function history()
    {
        return $this->hasMany(VehicleCGEquipUpdateHistory::class, 'source_id');
    }

    protected function getHistoryModelClass(): string
    {
        return VehicleCGEquipUpdateHistory::class;
    }

}
