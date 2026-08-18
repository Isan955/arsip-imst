<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'label'];

    public static function getValue(string $key, $default = null)
    {
        return Cache::rememberForever("profile.{$key}", function () use ($key, $default) {
            $profile = self::where('key', $key)->first();
            return $profile ? $profile->value : $default;
        });
    }

    public static function setValue(string $key, $value)
    {
        $profile = self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("profile.{$key}");
        return $profile;
    }
}
