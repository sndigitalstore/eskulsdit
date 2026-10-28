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
        if ($key === 'headmaster_nip' || $key === 'headmaster_niy') {
            if ($val !== null && $val !== '' && trim($val) !== '-') {
                return $val;
            }
            return '141502002';
        }

        if ($val !== null && $val !== '') {
            return $val;
        }

        if ($key === 'headmaster_name') {
            $headmasterUser = User::where('role', 'headmaster')->first();
            return $headmasterUser ? $headmasterUser->name : ($default ?? "Nur'asiah, S.Pd.I");
        }

        if ($key === 'school_name') {
            return $default ?? 'SDIT AN NADZIR';
        }

        if ($key === 'yayasan_name') {
            return $default ?? 'YAYASAN AN NADZIR ISLAMIC SCHOOL';
        }

        if ($key === 'school_address') {
            return $default ?? 'Bendungan Karet Cisirih KP. Cukang RT. 09 RW. 02 Desa Kamasan Kecamatan Cinangka Kabupaten Serang - Banten 42167';
        }

        if ($key === 'school_city') {
            return $default ?? 'Serang';
        }

        if ($key === 'npsn') {
            return $default ?? '20616174';
        }

        if ($key === 'nns') {
            return $default ?? '101280432037';
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
