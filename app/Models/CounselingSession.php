<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CounselingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'anonymous_user_id',
        'counselor_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(AnonymousUser::class);
    }

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class,'session_id');
    }
}