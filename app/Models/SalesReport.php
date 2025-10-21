<?php

// 15. SalesReport.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'report_number',
        'report_date',
        'period_start',
        'period_end',
        'report_period',
        'total_sales',
        'total_cost',
        'total_profit',
        'total_discount',
        'total_tax',
        'total_orders',
        'completed_orders',
        'cancelled_orders',
        'pending_orders',
        'total_customers',
        'new_customers',
        'generated_by',
        'notes',
    ];

    protected $casts = [
        'report_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'total_sales' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total_orders' => 'integer',
        'completed_orders' => 'integer',
        'cancelled_orders' => 'integer',
        'pending_orders' => 'integer',
        'total_customers' => 'integer',
        'new_customers' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getFormattedTotalSalesAttribute()
    {
        return 'Rp ' . number_format($this->total_sales, 0, ',', '.');
    }

    public function getFormattedTotalProfitAttribute()
    {
        return 'Rp ' . number_format($this->total_profit, 0, ',', '.');
    }

    public function getProfitMarginAttribute()
    {
        if ($this->total_sales <= 0) return 0;
        return round(($this->total_profit / $this->total_sales) * 100, 2);
    }

    public function getPeriodNameAttribute()
    {
        $periods = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'quarterly' => 'Triwulan',
            'yearly' => 'Tahunan',
            'custom' => 'Custom',
        ];
        return $periods[$this->report_period] ?? $this->report_period;
    }

    // Scopes
    public function scopeByPeriod($query, $period)
    {
        return $query->where('report_period', $period);
    }

    public function scopeRecent($query, $months = 3)
    {
        return $query->where('report_date', '>=', now()->subMonths($months));
    }

    // Relations
    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by', 'user_id');
    }
}