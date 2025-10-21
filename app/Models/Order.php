<?php

// 7. Order.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_number',
        'customer_id',
        'status_id',
        'order_date',
        'deadline',
        'completed_date',
        'subtotal',
        'discount_amount',
        'discount_percentage',
        'tax_amount',
        'tax_percentage',
        'total_price',
        'paid_amount',
        'remaining_amount',
        'notes',
        'customer_notes',
        'assigned_designer',
        'assigned_production',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'deadline' => 'date',
        'completed_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return 'Rp ' . number_format($this->remaining_amount, 0, ',', '.');
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->paid_amount <= 0) return 'unpaid';
        if ($this->remaining_amount <= 0) return 'paid';
        return 'partial';
    }

    public function getIsOverdueAttribute()
    {
        return $this->deadline && $this->deadline->isPast() && !$this->completed_date;
    }

    // Scopes
    public function scopeByStatus($query, $statusId)
    {
        return $query->where('status_id', $statusId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNull('completed_date')
                     ->where('deadline', '<', now());
    }

    public function scopeUnpaid($query)
    {
        return $query->where('remaining_amount', '>', 0);
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_date');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('order_date', '>=', now()->subDays($days));
    }

    // Relations
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id', 'status_id');
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'assigned_designer', 'user_id');
    }

    public function production()
    {
        return $this->belongsTo(User::class, 'assigned_production', 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'order_id');
    }

    public function designFiles()
    {
        return $this->hasMany(DesignFile::class, 'order_id', 'order_id');
    }

    public function productionProgress()
    {
        return $this->hasMany(ProductionProgress::class, 'order_id', 'order_id');
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'order_id', 'order_id');
    }

    public function completedPayments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'order_id')
                    ->where('payment_status', 'completed');
    }
}
