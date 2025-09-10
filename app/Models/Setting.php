<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value','group'];

    // ambil value, return default bila kosong
    public static function get($key, $default = null)
    {
        $s = static::where('key', $key)->first();
        return $s ? $s->value : $default;
    }

    // set value
    public static function set($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    // get as boolean
    public static function bool($key, $default = false)
    {
        $v = static::get($key, $default ? '1' : '0');
        return in_array($v, ['1', 1, 'true', true], true);
    }
}
