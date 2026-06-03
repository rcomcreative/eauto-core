<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Eauto\Core\Models\Traits\ArchivesNarrativeHistory;

class VehicleFutureIntel extends Model
{
    use HasFactory, Searchable, ArchivesNarrativeHistory;

    protected $table = 'vehicle_future_intels';

    protected $fillable = [
        'id',
        'admin_id',
        'vehicle_id',
        'futureIntel',
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
        return $this->hasMany(VehicleFutureIntelHistory::class, 'source_id');
    }

    protected function getHistoryModelClass(): string
    {
        return VehicleFutureIntelHistory::class;
    }

}
