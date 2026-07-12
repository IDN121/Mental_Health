<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_user_id',
        'sender',
        'message',
        'emotion',
        'confidence',
        'is_read',
        'status'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function anonymousUser()
    {
        return $this->belongsTo(AnonymousUser::class, 'anonymous_user_id');
    }
}