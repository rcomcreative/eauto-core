<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Eauto\Core\Models\Traits\ArchivesNarrativeHistory;

class VehicleConfigPowerTrainProfile extends Model
{
    use HasFactory, Searchable, ArchivesNarrativeHistory;

    protected $table = 'vehicle_config_power_train_profiles';

    protected $fillable = [
        'id',
        'admin_id',
        'vehicle_id',
        'vehicleConfigPowerTrainProfile',
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
        return $this->hasMany(VehicleConfigPowerTrainProfileHistory::class, 'source_id');
    }

    protected function getHistoryModelClass(): string
    {
        return VehiclePriorGenerationInfoHistory::class;
    }

}
