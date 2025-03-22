<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contacts'; // Đảm bảo đúng tên bảng

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'ip_address',
        'is_active',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'status' => 'string', // pending, resolved, spam
        'is_active' => 'boolean',
    ];

    public $attributes = [
        'is_active' => 1
    ];

    /**
     * Liên kết với bảng users (nếu có user_id).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
