<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class DepartmentCustomSegment extends Model
{
    use HasFactory, Searchable;

    protected $table = 'department_custom_segments';

    public $incrementing = false;   // id comes from live data
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'department_id',
        'segment_name',
    ];
}
