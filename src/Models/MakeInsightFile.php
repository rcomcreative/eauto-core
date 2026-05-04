<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;

class MakeInsightFile extends Model
{
    protected $table = 'make_insight_files';

    protected $fillable = [
        'make_id',
        'insight_file_id',
        'truck_flag',
    ];

    protected $casts = [
        'truck_flag' => 'boolean',
    ];

    /**
     * Relationships
     */

    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    public function insightFile()
    {
        return $this->belongsTo(InsightFile::class, 'insight_file_id');
    }
}
