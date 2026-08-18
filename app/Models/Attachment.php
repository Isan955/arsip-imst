<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_version_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type'
    ];

    public function version()
    {
        return $this->belongsTo(InformationVersion::class, 'information_version_id');
    }
}
