<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccessRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'accessible_id',
        'accessible_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accessible()
    {
        return $this->morphTo();
    }
}
