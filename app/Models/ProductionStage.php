<?php

// 12. ProductionStage.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'stage_id';

    protected $fillable = [
        'stage_name',
        'stage_code',
        'description',
        'sequence_order',
        'estimated_duration',
        'color',
        'is_active',
    ];

    protected $casts = [
        'sequence_order' => 'integer',
        'estimated_duration' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getFormattedDurationAttribute()
    {
        $minutes = $this->estimated_duration;
        if ($minutes >= 1440) {
            return round($minutes / 1440, 1) . ' hari';
        } elseif ($minutes >= 60) {
            return round($minutes / 60, 1) . ' jam';
        }
        return $minutes . ' menit';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence_order');
    }

    // Relations
    public function productionProgress()
    {
        return $this->hasMany(ProductionProgress::class, 'stage_id', 'stage_id');
    }
}