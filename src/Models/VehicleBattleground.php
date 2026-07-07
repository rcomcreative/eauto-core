<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleBattleground extends Model
{
    use HasFactory, Searchable;

    protected $table = 'vehicle_battlegrounds';

    protected $fillable = [
        'vehicle_id',
        'current_code_name',
        'future_code_name',
        'change_1',
        'change_2',
        'change_3',
        'american_region',
        'asia_region',
        'europe_region',
    ];

    protected $casts = [
        'change_1' => 'decimal:1',
        'change_2' => 'decimal:1',
        'change_3' => 'decimal:1',
        'american_region' => 'boolean',
        'asia_region' => 'boolean',
        'europe_region' => 'boolean',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
