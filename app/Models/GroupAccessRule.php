<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupAccessRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'accessible_id',
        'accessible_type'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function accessible()
    {
        return $this->morphTo();
    }
}
