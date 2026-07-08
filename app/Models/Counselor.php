<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counselor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function sessions()
    {
        return $this->hasMany(CounselingSession::class);
    }
}