<?php

// 6. OrderStatus.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'status_id';
    protected $table = 'order_statuses';

    protected $fillable = [
        'status_name',
        'status_code',
        'description',
        'color',
        'sequence_order',
        'is_active',
    ];

    protected $casts = [
        'sequence_order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

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
    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id', 'status_id');
    }
}
