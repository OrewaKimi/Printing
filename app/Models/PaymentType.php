<?php

// 9. PaymentType.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'payment_type_id';

    protected $fillable = [
        'type_name',
        'type_code',
        'minimum_percentage',
        'maximum_percentage',
        'description',
        'is_active',
    ];

    protected $casts = [
        'minimum_percentage' => 'decimal:2',
        'maximum_percentage' => 'decimal:2',
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

    // Relations
    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_type_id', 'payment_type_id');
    }
}
