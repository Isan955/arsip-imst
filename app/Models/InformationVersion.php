<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformationVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_id',
        'month',
        'year',
        'rich_content',
        'created_by'
    ];

    public function information()
    {
        return $this->belongsTo(Information::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
