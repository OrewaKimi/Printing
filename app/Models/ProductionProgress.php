<?php

// 13. ProductionProgress.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionProgress extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'progress_id';
    protected $table = 'production_progress';

    protected $fillable = [
        'order_id',
        'item_id',
        'stage_id',
        'status',
        'started_at',
        'completed_at',
        'paused_at',
        'duration',
        'handled_by',
        'progress_percentage',
        'notes',
        'issues',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'paused_at' => 'datetime',
        'duration' => 'integer',
        'progress_percentage' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getStatusNameAttribute()
    {
        $statuses = [
            'not_started' => 'Belum Dimulai',
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            'on_hold' => 'Ditunda',
            'cancelled' => 'Dibatalkan',
            'rejected' => 'Ditolak',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return '-';
        $minutes = $this->duration;
        if ($minutes >= 1440) {
            return round($minutes / 1440, 1) . ' hari';
        } elseif ($minutes >= 60) {
            return round($minutes / 60, 1) . ' jam';
        }
        return $minutes . ' menit';
    }

    // Scopes
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByStage($query, $stageId)
    {
        return $query->where('stage_id', $stageId);
    }

    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'item_id', 'item_id');
    }

    public function stage()
    {
        return $this->belongsTo(ProductionStage::class, 'stage_id', 'stage_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by', 'user_id');
    }
}
