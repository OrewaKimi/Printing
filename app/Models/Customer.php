<?php

// 2. Customer.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'user_id',
        'name',
        'company_name',
        'address',
        'phone',
        'email',
        'customer_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->company_name ?: $this->name;
    }

    public function getTypeNameAttribute()
    {
        return $this->customer_type === 'business' ? 'Bisnis' : 'Personal';
    }

    // Scopes
    public function scopeBusiness($query)
    {
        return $query->where('customer_type', 'business');
    }

    public function scopePersonal($query)
    {
        return $query->where('customer_type', 'personal');
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }
}