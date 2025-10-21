<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'password',
        'email',
        'phone',
        'role',
        'full_name',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->username)) {
                $emailUsername = explode('@', $user->email)[0];
                $username = $emailUsername;
                
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $emailUsername . $counter;
                    $counter++;
                }
                
                $user->username = $username;
            }

            if (empty($user->role)) {
                $user->role = 'customer';
            }
        });
    }

    // TAMBAHKAN METHOD INI - untuk Filament
    public function getFilamentName(): string
    {
        return $this->full_name ?? $this->username ?? $this->email;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin' && $this->is_active;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            if (preg_match('/^\$2[ayb]\$.{56}$/', $value)) {
                $this->attributes['password'] = $value;
            } else {
                $this->attributes['password'] = Hash::make($value);
            }
        }
    }

    public function getRoleNameAttribute()
    {
        $roles = [
            'customer' => 'Customer',
            'customer_service' => 'Customer Service',
            'production' => 'Production Staff',
            'designer' => 'Designer',
            'admin' => 'Administrator',
        ];
        return $roles[$this->role] ?? $this->role;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Relations
    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'user_id');
    }

    public function designedOrders()
    {
        return $this->hasMany(Order::class, 'assigned_designer', 'user_id');
    }

    public function productionOrders()
    {
        return $this->hasMany(Order::class, 'assigned_production', 'user_id');
    }

    public function createdOrders()
    {
        return $this->hasMany(Order::class, 'created_by', 'user_id');
    }

    public function updatedOrders()
    {
        return $this->hasMany(Order::class, 'updated_by', 'user_id');
    }

    public function uploadedDesignFiles()
    {
        return $this->hasMany(DesignFile::class, 'uploaded_by', 'user_id');
    }

    public function approvedDesignFiles()
    {
        return $this->hasMany(DesignFile::class, 'approved_by', 'user_id');
    }

    public function receivedPayments()
    {
        return $this->hasMany(Payment::class, 'received_by', 'user_id');
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by', 'user_id');
    }

    public function handledProgress()
    {
        return $this->hasMany(ProductionProgress::class, 'handled_by', 'user_id');
    }

    public function handledInventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'handled_by', 'user_id');
    }

    public function approvedInventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'approved_by', 'user_id');
    }

    public function generatedReports()
    {
        return $this->hasMany(SalesReport::class, 'generated_by', 'user_id');
    }
}