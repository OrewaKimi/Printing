<?php

// 11. DesignFile.php
// ========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DesignFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'file_id';

    protected $fillable = [
        'order_id',
        'item_id',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'mime_type',
        'file_category',
        'version',
        'uploaded_by',
        'uploaded_date',
        'is_approved',
        'approved_by',
        'approved_date',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'version' => 'integer',
        'uploaded_date' => 'datetime',
        'is_approved' => 'boolean',
        'approved_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessors
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getFormattedFileSizeAttribute()
    {
        $bytes = floatval($this->file_size);
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    public function getCategoryNameAttribute()
    {
        $categories = [
            'customer_upload' => 'Upload Customer',
            'designer_draft' => 'Draft Designer',
            'final_design' => 'Desain Final',
            'revision' => 'Revisi',
            'reference' => 'Referensi',
        ];
        return $categories[$this->file_category] ?? $this->file_category;
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('file_category', $category);
    }

    public function scopeLatestVersion($query)
    {
        return $query->orderBy('version', 'desc');
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

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }
}
