<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    protected $fillable = ['key', 'value'];

    /**
     * Retrieve a setting value by key with optional default fallback,
     * or delegate to Eloquent Builder get() if an array or null is passed.
     */
    public static function get($key = ['*'], $default = null)
    {
        if (is_array($key)) {
            return static::query()->get($key);
        }

        $val = static::where('key', $key)->value('value');
        if ($val !== null && $val !== '') {
            return $val;
        }

        if ($key === 'headmaster_name') {
            $headmasterUser = User::where('role', 'headmaster')->first();
            return $headmasterUser ? $headmasterUser->name : ($default ?? "Nur'asiah, S.Pd.I");
        }

        return $default;
    }

    /**
     * Store or update a setting value by key
     */
    public static function set($key, $value)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
