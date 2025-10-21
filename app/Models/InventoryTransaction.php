<?php

// 14. InventoryTransaction.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'transaction_number',
        'material_id',
        'transaction_type',
        'quantity',
        'price_per_unit',
        'total_cost',
        'stock_before',
        'stock_after',
        'order_id',
        'item_id',
        'transaction_date',
        'reference_number',
        'supplier_invoice',
        'notes',
        'handled_by',
        'approved_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'stock_before' => 'decimal:2',
        'stock_after' => 'decimal:2',
        'transaction_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getTypeNameAttribute()
    {
        $types = [
            'in' => 'Stok Masuk',
            'out' => 'Stok Keluar',
            'adjustment' => 'Penyesuaian',
            'return' => 'Retur',
            'waste' => 'Sisa/Rusak',
        ];
        return $types[$this->transaction_type] ?? $this->transaction_type;
    }

    public function getFormattedTotalCostAttribute()
    {
        return 'Rp ' . number_format($this->total_cost, 0, ',', '.');
    }

    // Scopes
    public function scopeIn($query)
    {
        return $query->where('transaction_type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('transaction_type', 'out');
    }

    public function scopeByMaterial($query, $materialId)
    {
        return $query->where('material_id', $materialId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('transaction_date', '>=', now()->subDays($days));
    }

    // Relations
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'item_id', 'item_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }
}
