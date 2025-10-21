<?php

// 8. OrderItem.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'material_id',
        'width',
        'height',
        'area',
        'quantity',
        'unit',
        'unit_price',
        'material_cost',
        'production_cost',
        'subtotal',
        'specifications',
        'notes',
    ];

    protected $casts = [
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'area' => 'decimal:2',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'production_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getDimensionsAttribute()
    {
        if (!$this->width || !$this->height) return null;
        return $this->width . ' x ' . $this->height . ' cm';
    }

    public function getTotalCostAttribute()
    {
        return $this->material_cost + $this->production_cost;
    }

    public function getProfitAttribute()
    {
        return $this->subtotal - $this->total_cost;
    }

    // Relations
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'material_id');
    }

    public function productionProgress()
    {
        return $this->hasMany(ProductionProgress::class, 'item_id', 'item_id');
    }

    public function designFiles()
    {
        return $this->hasMany(DesignFile::class, 'item_id', 'item_id');
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'item_id', 'item_id');
    }
}
